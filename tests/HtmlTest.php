<?php

use PHPUnit\Framework\TestCase;
use SimpleHtml\Html;

class HtmlTest extends TestCase
{

    public function testNest()
    {
        $r = Html::nest('div#wrapper/a#my.link[href=#][title=my link]');
        $this->assertEquals('<div id="wrapper"><a id="my" class="link" href="#" title="my link"></a></div>', $r);

        $r = Html::nest('div#my-wrapper/a#my.link[href=#][title=my link]');
        $this->assertEquals('<div id="my-wrapper"><a id="my" class="link" href="#" title="my link"></a></div>', $r);

        $r = Html::nest('.step/.circle+p', 10);
        $this->assertEquals('<div class="step"><div class="circle"></div><p>10</p></div>', $r);

        $r = Html::nest('.step/.circle+p', 10, ['class' => 'active']);
        $this->assertEquals('<div class="step active"><div class="circle"></div><p>10</p></div>', $r);

        $r = Html::nest('div.step/div.circle+p', 10);
        $this->assertEquals('<div class="step"><div class="circle"></div><p>10</p></div>', $r);

        $r = Html::nest('div.step/div.circle+p/span.before+%', 10);
        $this->assertEquals(
            '<div class="step"><div class="circle"></div><p><span class="before"></span>10</p></div>',
            $r
        );

        $r = Html::nest('div/span[data-name=test]', 'hello!');
        $this->assertEquals('<div><span data-name="test">hello!</span></div>', $r);

        $r = Html::nest('div/span[disabled]', 'hello!');
        $this->assertEquals('<div><span disabled="disabled">hello!</span></div>', $r);

        $r = Html::nest('div/table/tr/td', 'hello!');
        $this->assertEquals('<div><table><tr><td>hello!</td></tr></table></div>', $r);

        $r = Html::nest('div/table#some-id sss.my-class other_class/tr/td', 'hello!');
        $this->assertEquals(
            '<div><table id="some-id sss" class="my-class other_class"><tr><td>hello!</td></tr></table></div>',
            $r
        );

        $r = Html::nest('div/table#some-id#sss.my-class.other_class/tr/td', 'hello!');
        $this->assertEquals(
            '<div><table id="some-id sss" class="my-class other_class"><tr><td>hello!</td></tr></table></div>',
            $r
        );

        $r = Html::nest('div#first/table#some-id#sss.my-class.other_class/tr/td.last', 'hello!');
        $this->assertEquals(
            '<div id="first"><table id="some-id sss" class="my-class other_class"><tr><td class="last">hello!</td></tr></table></div>',
            $r
        );

        $r = Html::nest('div#first/table#some-id#sss.my-class.other_class[disabled][data-id=8]/tr/td.last', 'hello!');
        $this->assertEquals(
            '<div id="first"><table id="some-id sss" class="my-class other_class" disabled="disabled" data-id="8"><tr><td class="last">hello!</td></tr></table></div>',
            $r
        );
    }

    public function testZZ()
    {
        $r = Html::zz('input.quantity-field[type=number][name=quantity][step=1][readonly][data-sku=%]', 'SKU');
        $this->assertEquals(
            '<input type="number" class="quantity-field" name="quantity" readonly="readonly" step="1" data-sku="SKU">',
            $r
        );

        $r = Html::zz('div.card card-primary(a[href=%]%)', 'http://github.com/', 'link');
        $this->assertEquals('<div class="card card-primary"><a href="http://github.com/">link</a></div>', $r);

        $r = Html::zz('.circle([data-name=%]+.round%+p)+span%', 'step', 10, 20);
        $this->assertEquals(
            '<div class="circle"><div data-name="step"></div><div class="round">10</div><p></p></div><span>20</span>',
            $r
        );

        $r = Html::zz('.%(.circle+.round%+p)+span%', 'step', 10, 20);
        $this->assertEquals(
            '<div class="step"><div class="circle"></div><div class="round">10</div><p></p></div><span>20</span>',
            $r
        );

        $r = Html::zz('div.%(div.circle+div.round%+p)+span%', 'step', 10, 20);
        $this->assertEquals(
            '<div class="step"><div class="circle"></div><div class="round">10</div><p></p></div><span>20</span>',
            $r
        );
    }

    public function testTags()
    {
        $r = Html::br();
        $this->assertEquals('<br>', $r);

        $r = Html::li('point', ['class' => 'active']);
        $this->assertEquals('<li class="active">point</li>', $r);

        $r = Html::p('test', ['class' => 'description']);
        $this->assertEquals('<p class="description">test</p>', $r);
    }

}