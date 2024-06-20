<?php 

namespace GTG\MVC;

abstract class View 
{
    private static ?BuildContext $buildContext = null;
    private static ?string $viewsPath = null;
    private static ?string $errorPagePath = null;

    public static function getContext(): BuildContext 
    {
        self::$buildContext = self::$buildContext ?? new BuildContext(self::$viewsPath);
        return self::$buildContext;
    }

    public static function getErrorPagePath(): ?string
    {
        return self::$errorPagePath;
    }

    public static function setViewsPath(string $viewsPath): void
    {
        self::$viewsPath = $viewsPath;
    }

    public static function setErrorPagePath(string $errorPagePath): void
    {
        self::$errorPagePath = $errorPagePath;
    }
}