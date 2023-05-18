<?php
    namespace Util;

    class RotasUtil{
        
        public static function getRotas(){
            $urls = self::getUrls();
            
            $request['rota'] = strtoupper($urls[0][0]);
            $request['recurso'] = $urls[0][1] ?? null;
            $request['key'] = $urls[0][2] ?? null;
            $request['metodo'] = $_SERVER['REQUEST_METHOD'];
            $request['sort'] = $urls[1] ?? null;
            return $request;
        }

        public static function getUrls(){
            $uri = str_replace('/'.DIR_PROJETO, '', $_SERVER['REQUEST_URI']);
            $uri = explode('?', $uri);
            $urls = explode('/', trim($uri[0], '/'));
            $sorts = isset($uri[1]) ? explode('=', $uri[1]) : null;
            
            return [$urls, $sorts];
        }
    }