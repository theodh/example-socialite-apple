<p align="center"><img src="https://res.cloudinary.com/dtfbvvkyp/image/upload/v1566331377/laravel-logolockup-cmyk-red.svg" width="400"></p>

# Laravel Socialite Apple Example

## Installation
Configure [apple sign in](https://developer.apple.com/sign-in-with-apple/get-started/), a good starting point is the blog of [Aaron Parecki](https://developer.okta.com/blog/2019/06/04/what-the-heck-is-sign-in-with-apple)

Add the following variables in your .env file:

SOCIAL_CLIENTID_APPLE=YOUR_APPLE_CLIENT_ID

SOCIAL_CLIENT_SECRET_APPLE=[Client_secret](https://github.com/aaronpk/sign-in-with-apple-example/blob/master/client-secret.rb) 

SOCIAL_REDIRECT_APPLE=/social-auth/handle/apple/

The following points should be considered in order to use the apple provider in [socialite](https://github.com/laravel/socialite/issues/369):

### Email only in the first handle
You only get the email address in the first login of the user. You should save the email address (user->email) and the apple identifier (sub) (user->id). The second time you use this identifier to find the user in your laravel applications.
See [SocialAuthController -> getHandleCallback](https://github.com/theodh/example-socialite-apple/blob/155e35bcc16a2de8e6b90432a73bc0f9c9995c9d/src/app/Http/Controllers/Auth/SocialAuthController.php#L33)

### Handle a post request of the authorization token
Add your [authorization handle post](https://developer.apple.com/documentation/signinwithapplerestapi/generate_and_validate_tokens) request in the [VerifyCsrfToken](https://github.com/theodh/example-socialite-apple/blob/155e35bcc16a2de8e6b90432a73bc0f9c9995c9d/src/app/Http/Middleware/VerifyCsrfToken.php#L22), in this example: 
```
 protected $except = [
        '/social-auth/handle/apple'
    ];
```

### Client Secret
Refresh the client_secret apple key each six months (write a automatic cronjob)

A cronjob example with [client.rb](https://github.com/aaronpk/sign-in-with-apple-example/blob/master/client-secret.rb):
```
#!/usr/bin/env bash

source /usr/local/rvm/environments/ruby-2.6.3

cd /your_path_apple_sign_in/

ruby client.rb > apple_client.txt

/bin/cp -f apple_client.txt /your_laravel_path/storage/apple/apple_client.txt

chown your_linux_user/your_linux_group /your_laravel_path/storage/apple/apple_client.txt

```
### Private email replay
If the user is using his anonymous email-address, a standard email relay (mandrill, sendgrid) is not possible [at this moment](https://forums.developer.apple.com/thread/122270).

## If everything is working

```
Succesfull login: Save your_apple_identifer as apple_identifier in your db and randomabc@privaterelay.appleid.com 
user->email, you only get the email once!!), 
for the development you could delete your apple app https://appleid.apple.com/account/manage 
(security ->  to test this again
```

## Troubleshouting
### Invalid grant
* Invalid grant: check that your client_id and client_secret has the same service_id.
* Timeout

### Invalid state exception
* Initiate the apple request again, state is invalid.


## License

The Laravel framework and this example is open-source software licensed under the [MIT license](https://opensource.org/licenses/MIT).


