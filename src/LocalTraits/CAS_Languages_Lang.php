<?PHP

namespace JuHeData\CasLogin\LocalTraits;

use CAS_Languages_English;

class CAS_Languages_Lang extends CAS_Languages_English
{
    /**
     * Get the using server string
     *
     * @return string using server
     */
    public function getUsingServer()
    {
        return 'using server';
    }

    /**
     * Get authentication wanted string
     *
     * @return string authentication wanted
     */
    public function getAuthenticationWanted()
    {
        return 'CAS Authentication wanted!';
    }

    /**
     * Get logout string
     *
     * @return string logout
     */
    public function getLogout()
    {
        return 'CAS logout wanted!';
    }

    /**
     * Get the should have been redirected string
     *
     * @return string should habe been redirected
     */
    public function getShouldHaveBeenRedirected()
    {
        return 'You should already have been redirected to the CAS server. Click <a href="%s">here</a> to continue.';
    }

    /**
     * Get authentication failed string
     *
     * @return string authentication failed
     */
    public function getAuthenticationFailed()
    {
        return '';
    }

    /**
     * Get the your were not authenticated string
     *
     * @return string not authenticated
     */
    public function getYouWereNotAuthenticated()
    {
        return view('errors.cas_login_error')->with([
            'message' => config('juheCas.message'),
            'timeout' => config('juheCas.timeout'),
            'url' => config('juheCas.url')
        ])->render();
    }

    /**
     * Get the service unavailable string
     *
     * @return string service unavailable
     */
    public function getServiceUnavailable()
    {
        return 'The service `<b>%s</b>\' is not available (<b>%s</b>).';
    }
}
