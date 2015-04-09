<?php
/*
 * 处理和多边形计算相关的函数
 */

class CalcUtil {

    /**
     * get the center point of a polygon
     * 
     * @static
     * @access public
     * @return coordinate
     */
    public static function getCenterPointOfPolygon($polygon)
    {
        if (count($polygon) <= 0) {
            return array();
        }
        $count = count($polygon);
        $longitude = 0;
        $latitude = 0;
        foreach ($polygon as $item) {
            $longitude += $item['longitude'];
            $latitude += $item['latitude'];
        }

        $longitude = round($longitude / $count, 5);
        $latitude = round($latitude / $count, 5);

        return array('longitude'=>$longitude, 'latitude'=>$latitude);

    }

    /**
     * judge the point whether in a polygon
     * 
     * @static
     * @access public
     * 计算逻辑：射线法, 从point往任意方向画一条射线, 和所有的边交点之和为偶数表示景点外, 奇数景点内
     * @return true or false
     */
    public static function checkPointInPolygon($polygon, $point) {
        if (count($polygon) <= 0) {
            return false;
        }
        if (count($polygon) == 1) {
            if ($polygon[0]['longitude'] != $point['longitude'] || $polygon[0]['latitude'] != $point['latitude']) {
                return false;
            } else {
                return true;
            }
        }
        if (count($polygon) == 2) {//TODO
            return false;
        }
        //get the the ray
        $end['longitude'] = 180;
        $end['latitude'] = $point['latitude'];
        $ray = array($point, $end);

        //calc the number of the intersection
        $point_count = count($polygon);
        $number = 0;
        foreach ($polygon as $k=>$val) {
            $segment = array();
            if ($k == $point_count-1) {
                $segment = array($val, $polygon[0]);
            } else {
                $segment = array($val, $polygon[$k+1]);
            }
            $res = self::checkSegmentsIntersect($ray, $segment);
            if ($res) {
                $number ++;
            }
        }

        return ($number % 2 == 0) ? false : true;
    }

    /**
     * judge the two segments whether intersect
     * 
     * @static
     * @access public
     * @return true or false
     */
    public static function checkSegmentsIntersect($segment1, $segment2) {
        $s1p1 = $segment1[0];
        $s1p2 = $segment1[1];
        $s2p1 = $segment2[0];
        $s2p2 = $segment2[1];

        if (max($s1p1['latitude'], $s1p2['latitude']) < min($s2p1['latitude'], $s2p2['latitude'])) {
            return false;
        }
        if (max($s1p1['longitude'], $s1p2['longitude']) < min($s2p1['longitude'], $s2p2['longitude'])) {
            return false;
        }
        if (max($s2p1['latitude'], $s2p2['latitude']) < min($s1p1['latitude'], $s1p2['latitude'])) {
            return false;
        }
        if (max($s2p1['longitude'], $s2p2['longitude']) < min($s1p1['longitude'], $s1p2['longitude'])) {
            return false;
        }
        $mult1 = self::mult($s2p1, $s1p2, $s1p1);
        $mult2 = self::mult($s1p2, $s2p2, $s1p1);
        if ($mult1 * $mult2 < 0) {
            return false;
        }
        $mult1 = self::mult($s1p1, $s2p2, $s2p1);
        $mult2 = self::mult($s2p2, $s1p2, $s2p1);
        if ($mult1 * $mult2 < 0) {
            return false;
        }
        return true;
    }
    private static function mult($point1, $point2, $point3) {
        return ($point1['latitude'] - $point3['latitude']) * ($point2['longitude'] - $point3['longitude'])
                - ($point2['latitude'] - $point3['latitude']) * ($point1['longitude'] - $point3['longitude']);
    }

    /**
     * calc the distance between to points
     * unit: m 
     * @static
     * @access public
     * @return true or false
     */
    public function getDistance($lng1,$lat1,$lng2,$lat2) {
        $radLat1=deg2rad($lat1);//deg2rad()函数将角度转换为弧度
        $radLat2=deg2rad($lat2);
        $radLng1=deg2rad($lng1);
        $radLng2=deg2rad($lng2);
        $a=$radLat1-$radLat2;
        $b=$radLng1-$radLng2;
        $s=2*asin(sqrt(pow(sin($a/2),2)+cos($radLat1)*cos($radLat2)*pow(sin($b/2),2)))*6378.137*1000;
        return $s;
    }

}
