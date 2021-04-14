<?php

namespace App\FileSystem;

use Error, ZipArchive;
use App\Hashing\Hash;

class Fs
{

	static public $filename = "";

	static public $filelist = array();

	public static function copy($source, $destination)
	{

		if (copy($source, $destination)) {
			return true;
		}

		return false;
	}

	public static function copyr($source, $dest)
	{
		// Simple copy for a file
		if (is_file($source)) {
			return copy($source, $dest);
		}

		// Make destination directory
		if (!is_dir($dest)) {
			mkdir($dest);
		}

		// Loop through the folder
		$dir = dir($source);
		while (false !== $entry = $dir->read()) {
			// Skip pointers
			if ($entry == '.' || $entry == '..') {
				continue;
			}

			// Deep copy directories
			if ($dest !== "$source/$entry") {
				Fs::copyr("$source/$entry", "$dest/$entry");
			}
		}

		// Clean up
		$dir->close();
		return true;
	}

	public static function copy_directory($src, $dst)
	{
		$dir = opendir($src);
		@mkdir($dst);
		while (false !== ($file = readdir($dir))) {
			if (($file != '.') && ($file != '..')) {
				if (is_dir($src . '/' . $file)) {
					Fs::copy_directory($src . '/' . $file, $dst . '/' . $file);
				} else {
					copy($src . '/' . $file, $dst . '/' . $file);
				}
			}
		}
		closedir($dir);
	}

	public static function is_active_directory($dirname) 
	{
		if(is_dir($dirname)) {
			return true;
		}

		return false;
	}

	public static function create_directory($dirname)
	{
		return mkdir($dirname);
	}

	public static function rmdir($dirname)
	{
		rmdir($dirname);
	}

	public static function delete($filename)
	{

		if (unlink($filename)) {
			return true;
		}

		return false;
	}

	public static function exists($filename)
	{

		if (file_exists($filename)) {
			return true;
		}

		return false;
	}

	public static function filename($index)
	{
		$file_name = $_FILES[$index]["name"];

		return $file_name;
	}

	public static function reset() 
	{
		static::$filelist = array();
	}

	public static function uploadImage($properties, $extensions = null)
	{

		if (is_null($extensions)) {
			$extensions = ["jpg", "png", "gif", "bmp", "jpeg",  "webp", "JPG", "PNG", "BMP", "GIF", "JPEG",  "WEBP"];
		}


		$filefield = $properties["filename"];
		$path = $properties["path"];

		if (!file_exists($path)) {
			Fs::create_directory($path);
		}

		$new_name = null;
		if (array_key_exists("rename", $properties)) {
			if (!is_null($properties["rename"])) {
				$new_name = $properties["rename"];
			}
		}

		$file_name = $_FILES[$filefield]["name"];
		$file_tmp = $_FILES[$filefield]["tmp_name"];

		$file_array = explode('.', $file_name);
		$file_extension = end($file_array);

		if (in_array($file_extension, $extensions)) 
		{
			if (!is_null($new_name)) 
			{
				# rename file 
				# check if user add extension
				if (preg_match("/\./", $new_name)) 
				{
					$new_name_split = explode(".", $new_name);
					$new_name_ext =  end($new_name_split);
				} 
				else 
				{
					# if extension is not added use upload file extension
					$new_name = $new_name . "." . $file_extension;
					$new_name_ext = $file_extension;
				}

				if (isset($new_name_ext) && in_array($new_name_ext, $extensions)) 
				{
					$uploadFile = $path . $new_name;

					if (move_uploaded_file($file_tmp, $uploadFile)) 
					{
						self::$filename = $new_name;
						return true;
					}
				} 
				
				else if (!in_array($new_name_ext, $extensions)) 
				{
					throw new Error("the file new name does not have a valid extension.");
				}
			} 
			
			else 
			
			{

				$hash_name =  Hash::create($file_name, true);
				$file_name = $hash_name . ".$file_extension";
				$uploadFile = $path . $file_name;

				if (move_uploaded_file($file_tmp, $uploadFile)) 
				{
					self::$filename = $file_name;
					return true;
				}
			}
		} 
		
		else 
		
		{
			throw new Error("This file type is not supported");
		}
	}

	public static function uploadMultipleImage($properties, $extensions = null)
	{
		static::reset();

		if (is_null($extensions)) 
		{
			$extensions = ["jpg", "png", "gif", "bmp", "jpeg",  "webp", "JPG", "PNG", "BMP", "GIF", "JPEG",  "WEBP"];
		}


		$filefield = $properties["filename"];
		$path = $properties["path"];

		if (!file_exists($path)) 
		{
			Fs::create_directory($path);
		}

		$prefix_name = null;
		if (array_key_exists("prefix", $properties)) 
		{
			if (!is_null($properties["prefix"])) 
			{
				$prefix_name = $properties["prefix"];
			}
		}


		for($i = 0; $i < count($_FILES[$filefield]["tmp_name"]); $i++) 
		{

			$file_name = $_FILES[$filefield]["name"][$i];
			$file_tmp = $_FILES[$filefield]["tmp_name"][$i];
	

			$file_array = explode('.', $file_name);
			$file_extension = end($file_array);

	
			if (in_array($file_extension, $extensions)) 
			{

				if (!is_null($prefix_name)) 
				{
					# rename file 
					# check if user add extension
					$file_name = $prefix_name . "-" . $file_name;
					$uploadFile = $path . $file_name;
				} 
				
				else 
				
				{
					$hash_name =  Hash::create($file_name, true);
					$file_name = $hash_name . ".$file_extension";
					$uploadFile = $path . $file_name;
				}
				

				if (move_uploaded_file($file_tmp, $uploadFile)) 
				{
					array_push(static::$filelist, $file_name);
				}
			} 
		}

		if(count(static::$filelist)) {
			return true;
		} 

		return false;

	}

	public static function uploadFile($properties, $extensions = null)
	{

		if (is_null($extensions)) 
		{
			$extensions = [
				"zip", "pdf", "docx", "doc", "cdr", "psd", "html", "css",
				"php", "csv", "accdb", "xlsx", "txt"
			];
		}

		return Fs::uploadImage($properties, $extensions);
	}

	public static function unzip($zipfile, $destination)
	{
		$zip = new ZipArchive;
		
		if ($zip->open($zipfile) === TRUE) 
		{
			$zip->extractTo($destination);
			$zip->close();
			return true;
		}

		return false;
	}

	public static function get_filename()
	{
		return static::$filename;
	}

	public static function get_filelist()
	{
		return static::$filelist;
	}

	public static function rename($oldname, $newname)
	{
		$rn = rename($oldname, $newname);
		return $rn;
	}
}
