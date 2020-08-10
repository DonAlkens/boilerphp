<?php

namespace App\FileSystem;

use Error, ZipArchive;

class Fs
{

	static public $filename = "";

	static public function copy($source, $destination)
	{

		if (copy($source, $destination)) {
			return true;
		}

		return false;
	}

	static public function copyr($source, $dest)
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

	static public function copy_directory($src, $dst)
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

	static public function mkdir($dirname)
	{
		return mkdir($dirname);
	}

	static public function rmdir($dirname)
	{
		rmdir($dirname);
	}

	static public function delete($filename)
	{

		if (unlink($filename)) {
			return true;
		}

		return false;
	}

	static public function exists($filename)
	{

		if (file_exists($filename)) {
			return true;
		}

		return false;
	}

	static public function filename($index)
	{
		$file_name = $_FILES[$index]["name"];

		return $file_name;
	}

	static public function uploadImage($properties, $extensions = null)
	{

		if (is_null($extensions)) {
			$extensions = ["jpg", "png", "gif", "bmp", "jpeg", "JPG", "PNG", "BMP", "GIF", "JPEG"];
		}


		$filefield = $properties["filename"];
		$path = $properties["path"];

		if (!file_exists($path)) {
			Fs::mkdir($path);
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

	static public function uploadFile($properties, $extensions = null)
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

	static public function unzip($zipfile, $destination)
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

	static public function get_filename()
	{
		return self::$filename;
	}

	static public function rename($oldname, $newname)
	{
		$rn = rename($oldname, $newname);
		return $rn;
	}
}
