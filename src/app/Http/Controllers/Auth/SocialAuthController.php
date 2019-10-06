<?php namespace App\Http\Controllers\Auth;
use App\Http\Controllers\Controller;
use App\UserSocial;
use Illuminate\Support\Facades\Input;
use Laravel\Socialite\Two\InvalidStateException;
use Socialite;
class SocialAuthController extends Controller
{
    const PROVIDERS = ['facebook', 'google', 'twitter', 'linkedin', 'apple'];

    public function getProvider($provider)
    {
        $this->checkProvider($provider);
        return Socialite::driver($provider)->redirect();

    }
    public function getHandleCallback($provider)
    {
        $this->checkProvider($provider);

        $driver = Socialite::driver($provider);
        $user = $driver->user();

        switch($provider)
        {
            case 'apple':

                if(empty($user->id))
                {
                    dd("Apple identifier not found");
                }

                if(!empty($user->email))
                {
                    dd("Succesfull login: Save {$user->id} as apple_identifier in your db and {$user->email} user->email, you only get the email once!!), for the development you could delete your apple app https://appleid.apple.com/account/manage (security ->  to test this again");
                }
                else
                {
                    dd("Succesfull second login: Get the email from the database with the apple identifier: " . $user->id);
                }

                break;
            default:
                $driver = Socialite::driver($provider);
                $user = $driver->user();

                dd("Success default login");
                break;
        }
    }
    private function checkProvider($provider)
    {
        if(!in_array($provider, SocialAuthController::PROVIDERS))
        {
            abort(403, "Provider not correct");
        }
        $services = \Config::get('services.' . $provider);

        if($provider == "apple")
        {
            //Get the automatic created client secret in your cronjob
            if(file_exists(storage_path('/apple/apple_client.txt')))
            {
                $clientSecret = file_get_contents(storage_path('/apple/apple_client.txt'));
                if(!empty($clientSecret))
                {
                    $services['client_secret'] = $clientSecret;
                }
            }
        }

        if(empty($services['client_secret']))
        {
            throw new \Exception("Client secret not found");
        }

        \Config::set('services.' . $provider, $services);
    }
}