<?php
declare(strict_types=1);

namespace BugReport\Service;

use Webmozart\Assert\Assert;

class Config
{

    /**
     * @var bool
     */
    private $hasConfig = false;

    /**
     * @var string
     */
    private $githubPAT = '';

    public function __construct(string $configfile)
    {
        if (file_exists($configfile)) {
            $this->hasConfig = true;

            $config = json_decode(file_get_contents($configfile), true);

            $this->configure($config);
        }
    }

    public function hasConfig() : bool
    {
        return $this->hasConfig;
    }

    public function githubPersonalAccessToken() : string
    {
        return $this->githubPAT;
    }

    private function configure(array $config)
    {
        Assert::keyExists($config, 'github_personal_access_token');
        $this->githubPAT = $config['github_personal_access_token'];
    }
}
