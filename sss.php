<?php

$postdata = 'action=update&reactor=8694f154769b1fe1a6c174209b7df65c6418e31c&testCount=2&%5Fsession%5Fid=null&%5Fmethod=put&stage%5Fid=855&user%5Fid=608208&meltdown=d6cb85d2df974f85069959d9315a7dcf5183a3f6&battle%5Fevents=%5B%5B1%2C%22deploy%22%2C%22625%2C887%22%2C%22PulseTank%3A8%22%5D%2C%5B23%2C%22deploy%22%2C%22625%2C887%22%2C%22Spider%3A14%22%5D%2C%5B23%2C%22deploy%22%2C%22625%2C887%22%2C%22Chimera%3A10%22%5D%2C%5B127%2C%22end%22%2C%22lose%22%5D%5D&simulation%5Fdata=%7B%22boosted%5Fxp%22%3Anull%2C%22resources%22%3A%7B%22energy%22%3A0%2C%22gas%22%3A0%2C%22uranium%22%3A0%2C%22crystal%22%3A0%7D%2C%22units%22%3A%5B%7B%22deployed%22%3A8%2C%22unit%5Ftype%22%3A%22PulseTank%22%2C%22dead%22%3A8%7D%2C%7B%22deployed%22%3A10%2C%22unit%5Ftype%22%3A%22Chimera%22%2C%22dead%22%3A10%7D%2C%7B%22deployed%22%3A14%2C%22unit%5Ftype%22%3A%22Spider%22%2C%22dead%22%3A14%7D%5D%2C%22outcome%22%3A%22win%22%2C%22sha%22%3A%22e7d24d5cc50015e2f02ad27dffd8ced45ec3f28b%22%2C%22xp%22%3Anull%2C%22token%22%3A%22d70807a16764835b6dc690473bf992bef35d2ba02ca6c896df41171b7f2460ad%22%2C%22terrain%5Fartifacts%22%3A%7B%22craters%22%3A%5B%7B%7D%2C%7B%7D%2C%7B%7D%2C%7B%7D%2C%7B%7D%2C%7B%7D%2C%7B%7D%2C%7B%7D%2C%7B%7D%2C%7B%7D%2C%7B%7D%2C%7B%7D%2C%7B%7D%2C%7B%7D%2C%7B%7D%2C%7B%7D%2C%7B%7D%2C%7B%7D%2C%7B%7D%2C%7B%7D%2C%7B%7D%2C%7B%7D%2C%7B%7D%2C%7B%7D%2C%7B%7D%2C%7B%7D%2C%7B%7D%2C%7B%7D%2C%7B%7D%2C%7B%7D%2C%7B%7D%2C%7B%7D%5D%7D%2C%22buildings%22%3A%5B%5D%7D&abasis%5Fid=649094';

$url = 'http://sector15.c1.galactic.wonderhill.com/api/bases/649094/defense_simulator';

$RequestEvent = 'SF';
$ManifestVO = 'f0uR';
$EOM = 'l1f3';

$str = $url.$postdata.$RequestEvent.$ManifestVO.$EOM;

echo sha1($str);

function asp_build_query($params)
{
	return str_replace('_', '%5f', http_build_query($params));
}
