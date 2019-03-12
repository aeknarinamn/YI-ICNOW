<?php
  
namespace Laravel\Socialite\Two;
  
use Exception;
use Illuminate\Support\Arr;
// use YellowProject\LineUserProfile;
  
class LineProvider extends AbstractProvider implements ProviderInterface
{
    /**
     * The scopes being requested.
     *
     * @var array
     */
    protected $scopes = [];
  
    /**
     * {@inheritdoc}
     */
    protected function getAuthUrl($state)
    {
        // dd($this->buildAuthUrlFromBase('https://access.line.me/dialog/oauth/weblogin', $state));
        //dd($state);
        $url = $this->buildAuthUrlFromBase('https://access.line.me/oauth2/v2.1/authorize', $state);
        $url = str_replace('profile+openid+email+phone', 'profile%20openid%20email%20phone', $url);
        return $url;
    }
  
    /**
     * {@inheritdoc}
     */
    protected function getTokenFields($code)
    {
        // dd($code);
        if($code == null){
            abort(500);
        }else{
            return [
                'grant_type' => 'authorization_code',
                'client_id' => $this->clientId,
                'client_secret' => $this->clientSecret,
                'code' => $code,
                'redirect_uri' => $this->redirectUrl,
            ];
        }
        
    }
  
    /**
     * {@inheritdoc}
     */
    protected function getTokenUrl()
    {
        return 'https://api.line.me/oauth2/v2.1/token';
    }
  
    /**
     * {@inheritdoc}
     */
    protected function getUserByToken($token)
    {
        // dd($token);
        // $userUrl = 'https://api.line.me/oauth2/v2.1/token';
        $userUrl = 'https://api.line.me/v2/profile';
  
        $response = $this->getHttpClient()->get(
            $userUrl, $this->getRequestOptions($token)
        );

        // dd(json_decode($response->getBody(), true));
  
        $user = json_decode($response->getBody(), true);
  
        return $user;
    }
  
    /**
     * {@inheritdoc}
     */
    protected function mapUserToObject(array $user,array $subDatas)
    {
        // dd($response);
        return (new User)->setRaw($user)->map([
            'id' => $user['userId'],
            'name' => array_key_exists('displayName', $user)? $user['displayName'] : null,
            // 'nickname' => $user['displayName'],
            'email' => array_key_exists('email', $subDatas)? $subDatas['email'] : null,
            'phone_number' => array_key_exists('phone_number', $subDatas)? $subDatas['phone_number'] : null,
            'avatar' => array_key_exists('pictureUrl', $user)? $user['pictureUrl'] : null,
            // 'avatar_original' => $user['pictureUrl'],
        ]);
    }
  
    /**
     * Get the default options for an HTTP request.
     *
     * @return array
     */
    protected function getRequestOptions($token)
    {
        return [
            'headers' => [
                'Authorization' => 'Bearer '.$token,
            ],
        ];
    }
}