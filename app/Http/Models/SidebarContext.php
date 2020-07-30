<?php


namespace App\Http\Models;


class SidebarContext
{
    private $supportsFullscreen;
    private $permaLink;

    /**
     * SidebarContext constructor.
     *
     * @param boolean     $supportsFullscreen
     * @param string      $permaLink
     */
    public function __construct($supportsFullscreen, $permaLink)
    {
        $this->supportsFullscreen = $supportsFullscreen;
        $this->permaLink = $permaLink;
    }

    /**
     * @return boolean
     */
    public function doesSupportFullscreen() : bool
    {
        return $this->supportsFullscreen;
    }

    /**
     * @return string|null
     */
    public function getFullscreenLink() : ?string
    {
        return $this->permaLink . "?fullscreen=true";
    }

    /**
     * @return string
     */
    public function getPermaLink() : string
    {
        return $this->permaLink;
    }


}
