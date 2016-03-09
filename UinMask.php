<?php
/**
 * Created by JetBrains PhpStorm.
 * User: v_diwli
 * Date: 16-1-8
 * Time: ÏÂÎç12:33
 * To change this template use File | Settings | File Templates.
 */
class UinMask{
    const UIN_MAX_BYTE = 5;         // 281,474,976,710,655
    private static function Uin2ByteArray($uin) {
        $tmp = gmp_init($uin);

        $byte_arr = array();
        for ($i=0; $i<self::UIN_MAX_BYTE; ++$i) {
            if ($i > 0) {
                $tmp = gmp_div_q($tmp, 256);    //2^8
            }
            $byte_arr[] = gmp_intval(gmp_and($tmp, 0xFF));
        }

        return $byte_arr;
    }

    private static function MaskUin($uin, $mask_arr, $encode = true) {
        if ( !is_numeric($uin) ) {
            return '';
        }

        $tmp = gmp_init(0);
        $byte_arr = self::Uin2ByteArray($uin);
        if ($encode) {
            $tmp = gmp_mul(gmp_or($byte_arr[1], 0x80), 256);        // 36,310,271,995,674,623
        }

        for ($i=self::UIN_MAX_BYTE-1; $i>=0; --$i) {
            $tmp = gmp_add($tmp, $byte_arr[$mask_arr[$i]]);
            if ($i>0){
                $tmp = gmp_mul($tmp,256);
            }
        }

        return gmp_strval($tmp);
    }

    public static function Encode($ori_uin) {
        $mask_arr = array(3,0,1,4,2);
        return self::MaskUin($ori_uin, $mask_arr);
    }

    public static function Decode($en_uin) {
        $mask_arr = array(1,2,4,0,3);
        return self::MaskUin($en_uin, $mask_arr, false);
    }

}