<?php

namespace App\FileSystem;

class Image
{

    protected $path = '';


    protected $filename = '';

    public function __construct($filename = 'default.jpg')
    {
        $this->filename = $filename;
    }

    public function crop($image, $thumb_width, $thumb_height, $rename = null)
    {

        $filename = (!is_null($rename)) ? $rename : $this->filename;
        $tmp_file = $image;

        $image = imagecreatefromjpeg($tmp_file);
        $exif = exif_read_data($tmp_file);

        if (!empty($exif['Orientation'])) {
            switch ($exif['Orientation']) {
                case 3:
                    $image = imagerotate($image, -180, 0);
                    break;
                case 6:
                    $image = imagerotate($image, -90, 0);
                    break;
                case 8:
                    $image = imagerotate($image, -90, 0);
                    break;
            }
        }

        $width = imagesx($image);
        $height = imagesy($image);

        $original_aspect = $width / $height;
        $thumb_aspect = $thumb_width / $thumb_height;

        if ($original_aspect >= $thumb_aspect) {
            // If image is wider than thumbnail (in aspect ratio sense)
            $new_height = $thumb_height;
            $new_width = $width / ($height / $thumb_height);
        } else {
            // If the thumbnail is wider than the image
            $new_width = $thumb_width;
            $new_height = $height / ($width / $thumb_width);
        }

        $thumb = imagecreatetruecolor($thumb_width, $thumb_height);

        // Resize and crop
        imagecopyresampled(
            $thumb,
            $image,
            0 - ($new_width - $thumb_width) / 2, // Center the image horizontally
            0 - ($new_height - $thumb_height) / 2, // Center the image vertically
            0,
            0,
            $new_width,
            $new_height,
            $width,
            $height
        );

        imagejpeg($thumb, $this->path.$filename, 100);

        return $filename;
    }

    public function setPath($path) {
        $this->path = $path;
        return $this;
    }
}
