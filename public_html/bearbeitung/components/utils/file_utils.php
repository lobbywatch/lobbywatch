<?php

require_once 'string_utils.php';

class FileUtils
{
    /**
     * @param string
     * @return string
     */
    public static function ReadAllText($fileName)
    {
        return file_get_contents($fileName);
    }

    /**
     * @param string|null $dir
     * @return string
     */
    public static function GetTempFileName($dir = null)
    {
        return tempnam($dir == null ? sys_get_temp_dir() : $dir, "smpgtemp");
    }

    /**
     * @static
     * @param string $fileName
     * @return bool
     */
    public static function FileExists($fileName)
    {
        return file_exists($fileName);
    }

    /**
     * @static
     * @param string $fileName
     * @return bool
     */
    public static function RemoveFile($fileName)
    {
        return unlink($fileName);
    }

    /**
     * @static
     * @param string $fileName
     * @return bool
     */
    public static function IsUploadedFile($fileName)
    {
        return is_uploaded_file($fileName);
    }

    public static function MoveUploadedFile($value, $target, $replaceIfExists = false)
    {
        if ($replaceIfExists)
            if (FileUtils::IsUploadedFile($value))
                if (FileUtils::FileExists($target))
                    FileUtils::RemoveFile($target);
        move_uploaded_file($value, $target);
    }

    /**
     * Returns false if directory already exists
     * @static
     * @param string $path
     * @return bool
     */
    public static function ForceDirectories($path)
    {
        return @mkdir($path, 0777, true);
    }

    /**
     * @static
     * @param string $fileName
     * @param string $extension
     * @return string
     */
    public static function AppendFileExtension($fileName, $extension)
    {
        return "$fileName.$extension";
    }
}

class Path
{
    public static $PathDelimiter = '/';

    /**
     * @param string $character
     * @return bool
     */
    public static function IsPathDelimiter($character)
    {
        return $character == Path::$PathDelimiter;
    }

    /**
     * @param string $path
     * @return bool
     */
    public static function IsAbsolutePath($path)
    {
        if (strlen($path) > 0)
            return Path::IsPathDelimiter($path[0]);
        else
            return false;
    }

    /**
     * @param string $path
     * @return string
     */
    public static function IncludeTrailingPathDelimiter($path)
    {
        $result = $path;
        if (!Path::IsPathDelimiter($result[strlen($result) - 1]))
            $result .= Path::$PathDelimiter;
        return $result;
    }

    /**
     * @param string $prefix
     * @param string $suffix
     * @return string
     */
    public static function Combine($prefix, $suffix)
    {
        if (Path::IsAbsolutePath($suffix) || !isset($prefix) || empty($prefix))
            return $suffix;
        else
            return Path::IncludeTrailingPathDelimiter($prefix) . $suffix;
    }

    /**
     * @param string $filePath
     * @return string
     */
    public static function GetFileExtension($filePath)
    {
        return substr($filePath, strrpos($filePath, '.') + 1);
    }

    /**
     * @param string
     * @return mixed
     */
    public static function GetFileTitle($filePath)
    {
        return pathinfo($filePath, PATHINFO_BASENAME);
    }

    /**
     * @param string $fileName
     * @param string $replaceChar
     * @return string
     */
    public static function ReplaceFileNameIllegalCharacters($fileName, $replaceChar = '_')
    {
        $illegal_characters = array('\\', '/', ':', '*', '?', '<', '>', '|', '"', '#', ' ');
        $result = $fileName;
        foreach($illegal_characters as $character)
            $result = StringUtils::Replace($character, $replaceChar, $result);
        return $result;
    }
}

?>
