--TEST--
Connection strings: Test journal over mongos
--SKIPIF--
<?php $needs = "2.5.3"; $needsOp = "<" ?>
<?php require_once "tests/utils/mongos.inc" ?>
--FILE--
<?php
require_once "tests/utils/server.inc";

$cfg = MongoShellServer::getShardInfo();


function log_query($server, $query, $cursor_options) {
    var_dump($query);
}

$ctx = stream_context_create(
    array(
        "mongodb" => array(
            "log_query" => "log_query",
        )
    )
);

$mc = new MongoClient($cfg[0], array("journal" => true), array("context" => $ctx));

echo "journal enabled by default\n";
$doc = array("doc" => "ument");
$mc->test->bug572->insert($doc);
$mc->test->bug572->update(array("_id" => $doc["_id"]), array("updated" => "doc"));
$mc->test->bug572->remove(array("_id" => $doc["_id"]));

echo "Setting it to false, per-query\n";
$doc = array("doc" => "ument");
$mc->test->bug572->insert($doc, array("j" => false));
$mc->test->bug572->update(array("_id" => $doc["_id"]), array("updated" => "doc"), array("j" => false));
$mc->test->bug572->remove(array("_id" => $doc["_id"]), array("j" => false));

echo "Setting it to false, per-query, and w=0 to force no-gle\n";
$doc = array("doc" => "ument");
$mc->test->bug572->insert($doc, array("j" => false, "w" => 0));
$mc->test->bug572->update(array("_id" => $doc["_id"]), array("updated" => "doc"), array("j" => false, "w" => 0));
$mc->test->bug572->remove(array("_id" => $doc["_id"]), array("j" => false, "w" => 0));

$mc = new MongoClient($cfg[0], array("journal" => false), array("context" => $ctx));

echo "journal disabled by default\n";
$doc = array("doc" => "ument");
$mc->test->bug572->insert($doc);
$mc->test->bug572->update(array("_id" => $doc["_id"]), array("updated" => "doc"));
$mc->test->bug572->remove(array("_id" => $doc["_id"]));

echo "Setting it to true, per-query\n";
$doc = array("doc" => "ument");
$mc->test->bug572->insert($doc, array("j" => true));
$mc->test->bug572->update(array("_id" => $doc["_id"]), array("updated" => "doc"), array("j" => true));
$mc->test->bug572->remove(array("_id" => $doc["_id"]), array("j" => true));

$mc = new MongoClient($cfg[0], array("journal" => false, "w" => 0), array("context" => $ctx));

echo "journal disabled by default, and gle\n";
$doc = array("doc" => "ument");
$mc->test->bug572->insert($doc);
$mc->test->bug572->update(array("_id" => $doc["_id"]), array("updated" => "doc"));
$mc->test->bug572->remove(array("_id" => $doc["_id"]));

echo "Setting it to true, per-query, with gle=0\n";
$doc = array("doc" => "ument");
$mc->test->bug572->insert($doc, array("j" => true));
$mc->test->bug572->update(array("_id" => $doc["_id"]), array("updated" => "doc"), array("j" => true));
$mc->test->bug572->remove(array("_id" => $doc["_id"]), array("j" => true));

?>
--EXPECTF--
journal enabled by default
array(2) {
  ["getlasterror"]=>
  int(1)
  ["j"]=>
  bool(true)
}
array(2) {
  ["getlasterror"]=>
  int(1)
  ["j"]=>
  bool(true)
}
array(2) {
  ["getlasterror"]=>
  int(1)
  ["j"]=>
  bool(true)
}
Setting it to false, per-query
array(1) {
  ["getlasterror"]=>
  int(1)
}
array(1) {
  ["getlasterror"]=>
  int(1)
}
array(1) {
  ["getlasterror"]=>
  int(1)
}
Setting it to false, per-query, and w=0 to force no-gle
journal disabled by default
array(1) {
  ["getlasterror"]=>
  int(1)
}
array(1) {
  ["getlasterror"]=>
  int(1)
}
array(1) {
  ["getlasterror"]=>
  int(1)
}
Setting it to true, per-query
array(2) {
  ["getlasterror"]=>
  int(1)
  ["j"]=>
  bool(true)
}
array(2) {
  ["getlasterror"]=>
  int(1)
  ["j"]=>
  bool(true)
}
array(2) {
  ["getlasterror"]=>
  int(1)
  ["j"]=>
  bool(true)
}
journal disabled by default, and gle
Setting it to true, per-query, with gle=0
array(2) {
  ["getlasterror"]=>
  int(1)
  ["j"]=>
  bool(true)
}
array(2) {
  ["getlasterror"]=>
  int(1)
  ["j"]=>
  bool(true)
}
array(2) {
  ["getlasterror"]=>
  int(1)
  ["j"]=>
  bool(true)
}
