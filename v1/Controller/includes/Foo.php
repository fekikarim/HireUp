<?php 

class Foo {
    public function bar() {
        $test = 'This is a test from a class';
        $test .= '<br><img src="images/1.jpg" style="width:300px; height:auto;">';
        $test .= '
        <ul>
            <li>Item 1</li>
            <li>Item 2</li>
            <li>Item 3</li>
            <li>Item 4</li>
        </ul>';
        return $test;
    }
}