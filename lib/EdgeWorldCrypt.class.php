<?php

class EdgeWorldCrypt {

    const DELIM1 = '_';
    const DELIM2 = '+';
    const K1 = 'uTMF5efQQwzvRtX0nwXIBl8UGwLgqUVORwqkKJ8BmDrGklN4qkhGFYgFVUczosZa3hW87NB9nUrCcGuqufMbbT9KaAjmgeUk0bNNPuG3R3lNxFJ7vzsRU4ZANuUwITvmd0n6C43Dv0KpVs6ktTCJjR0unrM6S90LKS3PawWBf8WMoRw0rSnGDQjPSdxefaLrJqoQa5VFThXAHLivFe7QHLheLwOkEEzoJvKRNBdLaZ2srPV8APzl5RWaOqfOw6BDQTqrL5Ud758vQ7m18stDEQId7kCiy6zoyUbbgV4ZlTMbqkUZaI2B4j40KNmJSQHs4SZSux0dGgyGtkhtXQzYP7pPSJ1npSPVxu94PDxh4KpQKC2kBavzBxKQJ8gb5du29OG9pt054UhfvXfdbMoTx42F4PBqImO05IJwzaIIQuuXfqh7N7sPsKQPJfRuLuOdpoFaudHA57GyE3GMQEHc402ZtFaeSnBBQvk8upbjalTtywoE5YzFkaJqxlUWqbKmjw8sbnsCNXZvhhWmseZZpjICwinSuh18bqbUlSUN74KYCC045glTFwprjFWbHim9nMQl18naBe6bFVd5mrfR9HSPtKCtWTIQXSHJmCpJPqB1lDrKCwVJVOD9zatzbJK4ltZLDb0uwNHl1NtkvuMnuwzyNoGSz4lVpdcGlu6WhdRez1phY4ZJqSSo5XBbTatLbmdhV0u2CaSPzg8ecqzXOnZfhuo5buwD1Owm2RcNPVDz1BEZgJdTYAL17M1';
    const K2 = '5ZguUFDTSnHZDW3bOwjlH4kpIOcPtwus8Oqul1h4VMPMdgBXTqpwyfop5G4HbESlx0B6EDteRFnDsOwSsfd7BovDJc8EHhNmVhM06diS81E8HODhVyImAwQQ8LshJKCzIjoz41w0zNw4Mr6lXj7LnGkyn82z2O6rzuY4p3dpSkNJwZDic9BCY9U5URQaySnstK8mtPnN9pflPBvN7TR3HHbdIut5W3Fmk7OuUIjep07A7un4BHWrIkjTJuuY9gvvAufhyOYYR1H0qm6gvGkRZm7DRdGHATz8Lq7Z92ZuopFAhzuUGDZh0hlNwnqaHh2Oq73P65Le8BeUQwA3wqs9N8EwndG22jhNbAUtgIlPorXOXi3MbkbJAb2vsuJfkBcnjKaTPZ0Kne54cxErc4mpirCSOgNfIoJQsKXplOhiJyzrdPBcPHF6hLEak54597peKzmAZUU8KchVl7jAwp5IeVScDzNU3dWpGkZR7vOCarNzp2OG8r7zPiWyygXhPkSeiVHwaf3gDAaNlJKVMuXMQ1zp16GIUJYp9T7MIc3byqrw08JN28liRmDtUVVv';
    const K3 = 'Dz5PADiqnQ2ZueBdoPOCt6vDLfFa66BCz0WAZF50Ijb3PQiEis5snJAs9asxtaLvxvoaLMbISfIJwt5U6pXg16QVpfubpAUZYhxO3vPEWGtxGcb25jOGR1ZgA7H41Vsq7awPbHBQk97oxJzV1ObD1FVuuZ9slV1EwSuWSag1DTooFkC1IRpUzozBy4UPIwCqNyn8pjWBfADIn4PSlHWzXRhBvWed2WnTkIl9VbzxXAf9ZfMDLPoIfvWpevbThBdMymBy1GvRBPyxDJyyG6B3fhLBdmNEsVWv25WOMUsI9JRxNjqzqhAIKMkGlezhtA6MhgUYJDsMmgZ6swRdyfZP81qabpOLAkGMMwTchwgdFXWgqsoJbhbWQapIvtTeCTtNNOMhmo3br9ONiqyzHSK327M7pjFI3rkXYKQ1cJs97B0FBo8AoDvPpXeizCG4C73QsLLq99CmHn2t8F6Aiav0mhJdaTGZbSKhXkuc9OZCTZylfmjWYFwXugUDlIqSMvmN88SPdTlK75OaeszfynxpY0wAfFYtItoks2cMz4lgqDjbl8mlHl24Hmk3Vu7P';
    const K4 = 'CDAQVDyafQA8fG60JWu0Ic3z3IJhrFyNfLeLRVTbAqe54asg40sytr1iLJJY3aJaUq4Hqfj2SHDmhTOLbM9Lx9aBPzH8xpJiwc9qHOCNZxCETjD3AzM2gwA9zST1HmJJ9GU24hg4CdI0vf35kmcBSYalAMQBqY8v8wIvWc9HJSZjY8JWzh1b0GrmU8Ke0X4UJhPdgxlcwEpCaL5xIZ3AMRwDrupzFyQFLDS853MsSQE8J3jVU8HiGbXxgaAzBEDlCoMpbWxo6UHN5ENxqzDKdMgLmL9yOraWQMtLeXMWBRWTehfe6C69B8r4TVNON4ZXfApxMx8zBai9uFMsPF7saCxE8KtvarqQiDat5LQxrDyaAznDjdQWl3iiafceTNhrt64EAvYlHPETx5WHGOQLujODSSB7s11WXH8QcwWMqw377m8PThj9H6DYrEvVTPkc4SO2NpSoPLLWxha9UDJO5VJs3MORQ71TuTI8mG3Jlu51L22JN6JM7JZa7v2V0cJzH5eBMmmGtAeqPLuKRcErPENSEoblQOfSBNGC9xP3ViDUdNdC0bG7qgaLGrGy';
    const K5 = 'CMNWbQ9F22inh8QTHxPBH17DEsYKnkf9idTyj9B8Da1GsQcLCIU88vZtL6MUuDOKprgHD7h9CAO4IfJzaDIg4inQq3T6nsXIjqTcnlGJDtdrZIiywn401l14Un1UAwtAZcWk8SeGtZkvy6AqW8DxKY9UTmeUWF0V6zlxMDSXUs8TySiXn8Kq1WGMtpjHgSOSV3iANj8C1YrTNO6OobJ2DZrCcxcLlGUcGAMOk49fIAuFPJVKePjzIyji0GDw7wjLDTTXgiLKCoSqH851qiTnKir8UroqyS9Q5af4m99cA6h747xkY9tlLvmn6QE9cWebCGkIMbYz8d6rXa3NYQuXM8sjB9k6KfLiA7uBMVzsSEUU8U3WcmkiTXk8kozyXrZlwVuVE1rcMH2QguQsQkZddmf2xu8W0Y9REiu7ieymymv7ipYDfnlgBEb8FmVNsvzLGOr5AGSSPe2Jkvzq2PzE3mU48LEfTTPHYKKouwDJy9Ek65wWurHAAniTRSPEWJJdUIIxaSwi0Ruhq1ztN4h2yd0jtrjN86wpwF6m2o4yTWz4RpnL2Lfh0WheHbsK';
    const K6 = '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_0D0D0y1D3D5742y2D0D0y0D3D0y2D3D0_68H122H40H64H25H52H61H25H51H34H21H61H31H10H57H56H64H254H9H57H54H89H0H43H10H29H7H54H25H101H34H7086H122H10H29H14306H142H29H72H142H1H2H231H122H254H75H72H5H1H171+7086C68C24C0C142C31C16C89C51C7C80C7C0C14C43C64C89C111C51C14306C110C61C13C7086C46C111C103C17C53C29C43C0C122C82C5C52C8C6C17C122C79C39C14C18C46C14C56C80C111C9+54V80V46V0V64V9V14306V54V53V242V57V40V10V31V142+142I40I0I177I7086I34I10I14306I57I16I111I29I15I14306I43I111+68B0B72B128B6B30B108B134B31B56B88B56B128B44B64B35B134B124B31B297B16B8B49B68B20B124B33B127B12B60B64B128B103B9B32B41B29B48B127B103B125B95B44B38B20B44B24B88B124B85';

    public static function key($_arg1, $_arg2, $_arg3, $_arg4) {

        $_local5 = explode(self::DELIM1, self::K1 . self::K2 . self::K3 . self::K4 . self::K5 . self::K6 . self::DELIM1 . self::K1);
        $_local6 = $_local5[0];
        $_local7 = explode($_arg1, $_local5[1]);
        $_local8 = explode($_arg4, explode(self::DELIM2, $_local5[2])[$_arg3]);
        $i = 0;
        while ($i < count($_local7)) {
            $_local10 = explode($_arg2, $_local7[$i]);
            $_local9 = intval($_local10[0]);
            $_local11 = intval($_local10[1]);
            $_local12 = intval($_local10[2]);
            switch ($_local9) {
                case 0:
                    $_local6 = self::func002($_local6, $_local11, $_local12);
                    break;
                case 1:
                    $_local6 = self::func003($_local6, $_local11, $_local12);
                    break;
                default:
                    $_local6 = self::func002($_local6, $_local11, $_local12);
            }
            $i++;
        }
        return self::func001("", $_local6, $_local8);
    }

    private static function func001($str, $a, $b) {
        if (count($b)) {
            $i = array_shift($b);
            return self::func001($str . $a[$i], $a, $b);
        }
        return $str;
    }

    private static function func002($str, $a, $b) {
        $result = [];
        for ($i = 0;$i < ((($a - $b) % 2) + 1); ++$i) {
            foreach (explode($str[$i], $str) as $e) {
                $result[] = $e;
            }
        }
        return implode('', $result);
    }

    private static function func003($str, $unused, $delim_index) {
        $data = explode($str[$delim_index], $str);
        for ($i = 0; $i < count($data); $i += 2) {
            $data[$i] = ($i < 5) ?
                    self::strInvCase($data[$i]) :
                    self::strReverse($data[$i]);
        }
        return implode('', $data);
    }

    // замена регистра символов - большие становятся маленькими, маленькие - большими
    private static function strInvCase($str) {
        $result = "";
        $uc = strtoupper($str);
        $lc = strtolower($str);
        $i = 0;
        for ($i = 0; $i < strlen($str); ++$i) {
            $result .= ($str[$i] == $uc[$i]) ? $lc[$i] : $uc[$i];
        }
        return $result;
    }

    private static function strReverse($str) {
        return strrev($str);
    }
}