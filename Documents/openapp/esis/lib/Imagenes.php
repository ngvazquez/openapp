<?php
class Imagenes{
    private static $src;
    private static $name;
    private static $width;
    private static $height;
    private static $resource;
    private static $type;
    private static $saveDir;
    private static $mime;
    private static $error = false;

    public static function getError(){
        return self::$error;
    }

    public static function Procesar($array, $saveDir){
        // Setea la direccion del archivo subido
        self::$src = $array['tmp_name'];
        // Setea el Nombre
        self::$name = strtolower($array['name']);
        // Setea el MIME TYPE
        self::$mime = $array['type'];
        $arr = explode ('/',self::$mime);
        self::$mime = $arr[1];
        unset($arr);

        // Setea el tipo de extension
        self::$type = self::getExtension();

        if (!self::getError()){
            // Setea el directorio donde se va a guardar
            self::setDir($saveDir);
            // Obtiene las dimensiones (seteo de width y height)
            $newName = substr(self::$src,0,count(self::$src)-4).self::$type;
            $infoImage = getimagesize(self::$src);
            self::$width = $infoImage[0];
            self::$height = $infoImage[1];
        }
    }

    public static function ReSize($ancho, $alto, $forzar = false){
        if (!self::getError()){
            $width_out = $ancho;
            $height_out = $alto;

            if ($forzar){
                $width_out = $ancho;
                $height_out = $alto;
            }else{
                // Obtengo el porcentaje redimension
                $porc['w'] = self::$width/$width_out;
                $porc['h'] = self::$height/$height_out;

                if ($porc['w']<$porc['h']){
                        $porc = $porc['w'];
                }else{
                        $porc = $porc['h'];
                }

                // Obtengo el tama�o de la imagen redimensionada
                $width_out = round(self::$width/$porc);
                $height_out = round(self::$height/$porc);
            }

            // Creo las imagenes
            $resource["in"] = call_user_func('imagecreatefrom'.self::$mime,self::$src);
            $resource["out"] = imagecreatetruecolor($width_out, $height_out);

            /*$Trasparent = imagecolortransparent($resource["in"]);
            if($Trasparent != -1){
                $Trasparent = imagecolorsforindex($resource["in"], $Trasparent);
                $ReturnImgAlpha = imagecolorallocatealpha($resource["out"], $colorTransparente['red'], $colorTransparente['green'], $colorTransparente['blue'], $colorTransparente['alpha']); // Asigna un color en una imagen retorna identificador de color o FALSO o -1 apartir de la version 5.1.3
                imagefill($resource["out"], 0, 0, $ReturnImgAlpha);
                imagecolortransparent($resource["out"], $ReturnImgAlpha);
            }*/

            // Redimensiono la imagen
            imagecopyresampled ( $resource["out"], $resource["in"], 0, 0, 0, 0, $width_out, $height_out, self::$width, self::$height);

            // Actualizo las propiedades de la imagen
            self::$width = $width_out;
            self::$height = $height_out;
            self::$resource = $resource["out"];

            return self::$resource;
        }
    }

    public static function Cut($ancho, $alto){
        if (!self::getError()){
            self::resize($ancho, $alto);
            // Centro la imagen a cortar
            $x=(int)(self::$width/2)-(int)($ancho/2);
            $y=(int)(self::$height/2)-(int)($alto/2);
            // Creo las imagenes
            $resource["out"] = imagecreatetruecolor($ancho,$alto);
            // Corto la imagen
            imagecopy ( $resource["out"], self::$resource, 0,0,$x,$y, $ancho, $alto);
            // Actualizo las propiedades de la imagen
            self::$resource = $resource["out"];
            
            return self::$resource;
        }
    }

    public static function Save($saveDir=false, $newName = false){
        if (!self::getError()){
            if($saveDir){
                self::setDir($saveDir);
            }
            if($newName){
                self::setName($newName);
            }

            $onlyName = substr(self::$name,0,count(self::$name)-5);
            $newName = self::$name;

            // Controla que no sobrescriba
            $c=1;
            while (is_file(self::$saveDir.$newName)){
                $newName = $onlyName.'_' . sprintf('%04d',$c).'.'.self::$type;
                $c++;
            }

            self::setName($newName);

            // Guarda la imagen
            call_user_func('image'.self::$mime,self::$resource, self::$saveDir.self::$name);
        }
    }

    public static function getExtension(){
        switch(self::$mime){
            case 'gif':
                return "gif";
            break;
            case 'png':
                return "png";
            break;
            case 'jpg':
            case 'jpeg':
            case 'pjpeg':
                return "jpg";
            break;
            default:
                self::$error = "Tipo de archivo no soportado.";
                return false;
            break;
        }
    }

    public static function setDir($saveDir){
        self::$saveDir = $saveDir;
    }

    public static function setName($newName){
        self::$name = $newName;
    }

}
