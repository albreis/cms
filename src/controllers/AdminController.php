<?php namespace albreis\cms\controllers;

use CMS;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;

class AdminController extends CMSController
{
    function getIndex()
    {
        $data = [];
        $data['page_title'] = '<strong>Dashboard</strong>';

        return view('cms::home', $data);
    }

    public function getLockscreen()
    {

        if (! CMS::myId()) {
            Session::flush();

            return redirect()->route('getLogin')->with('message', cbLang('alert_session_expired'));
        }

        Session::put('admin_lock', 1);

        return view('cms::lockscreen');
    }

    public function postUnlockScreen()
    {
        $id = CMS::myId();
        $password = request('password');
        $users = DB::table(config('cms.USER_TABLE'))->where('id', $id)->first();

        if (\Hash::check($password, $users->password)) {
            Session::put('admin_lock', 0);

            return redirect(CMS::adminPath());
        } else {
            echo "<script>alert('".cbLang('alert_password_wrong')."');history.go(-1);</script>";
        }
    }

    public function getLogin()
    {

        if (CMS::myId()) {
            return redirect(CMS::adminPath());
        }

        return view('cms::login');
    }

    public function postLogin()
    {

        $validator = Validator::make(Request::all(), [
            'email' => 'required|email|exists:'.config('cms.USER_TABLE'),
            'password' => 'required',
        ]);

        if ($validator->fails()) {
            $message = $validator->errors()->all();

            return redirect()->back()->with(['message' => implode(', ', $message), 'message_type' => 'danger']);
        }

        $email = Request::input("email");
        $password = Request::input("password");
        $users = DB::table(config('cms.USER_TABLE'))->where("email", $email)->first();

        if (\Hash::check($password, $users->password)) {
            $priv = DB::table("cms_privileges")->where("id", $users->id_cms_privileges)->first();

            $roles = DB::table('cms_privileges_roles')->where('id_cms_privileges', $users->id_cms_privileges)->join('cms_moduls', 'cms_moduls.id', '=', 'id_cms_moduls')->select('cms_moduls.name', 'cms_moduls.path', 'is_visible', 'is_create', 'is_read', 'is_edit', 'is_delete')->get();

            $photo = ($users->photo) ? asset($users->photo) : asset('vendor/cms/avatar.jpg');
            Session::put('admin_id', $users->id);
            Session::put('admin_is_superadmin', $priv->is_superadmin);
            Session::put('admin_name', $users->name);
            Session::put('admin_photo', $photo);
            Session::put('admin_privileges_roles', $roles);
            Session::put("admin_privileges", $users->id_cms_privileges);
            Session::put('admin_privileges_name', $priv->name);
            Session::put('admin_lock', 0);
            Session::put('theme_color', $priv->theme_color);
            Session::put("appname", get_setting('appname'));

            CMS::insertLog(cbLang("log_login", ['email' => $users->email, 'ip' => Request::server('REMOTE_ADDR')]));

            $cb_hook_session = new \App\Http\Controllers\CMSHook;
            $cb_hook_session->afterLogin();

            return redirect(CMS::adminPath());
        } else {
            return redirect()->route('getLogin')->with('message', cbLang('alert_password_wrong'));
        }
    }

    public function getForgot()
    {
        if (CMS::myId()) {
            return redirect(CMS::adminPath());
        }

        return view('cms::forgot');
    }

    public function postForgot()
    {
        $validator = Validator::make(Request::all(), [
            'email' => 'required|email|exists:'.config('cms.USER_TABLE'),
        ]);

        if ($validator->fails()) {
            $message = $validator->errors()->all();

            return redirect()->back()->with(['message' => implode(', ', $message), 'message_type' => 'danger']);
        }

        $rand_string = str_random(5);
        $password = \Hash::make($rand_string);

        DB::table(config('cms.USER_TABLE'))->where('email', Request::input('email'))->update(['password' => $password]);

        $appname = CMS::getSetting('appname');
        $user = CMS::first(config('cms.USER_TABLE'), ['email' => g('email')]);
        $user->password = $rand_string;
        CMS::sendEmail(['to' => $user->email, 'data' => $user, 'template' => 'forgot_password_backend']);

        CMS::insertLog(cbLang("log_forgot", ['email' => g('email'), 'ip' => Request::server('REMOTE_ADDR')]));

        return redirect()->route('getLogin')->with('message', cbLang("message_forgot_password"));
    }

    public function getLogout()
    {

        $me = CMS::me();
        CMS::insertLog(cbLang("log_logout", ['email' => $me->email]));

        Session::flush();

        return redirect()->route('getLogin')->with('message', cbLang("message_after_logout"));
    }
}
