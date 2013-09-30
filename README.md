<p>Crawler - PHP Library, part of wi-framework.</p>
<p>Library allow to parse site bodies, find forms from sources, autocomplete html forms and send these forms to server for save.</p>
<p>So, it is spam bot.</p>
<br />
<br />
<p>
	Пример запуска парсера:
</p>
<pre>
APPLICATION_ENV=local ../../bin/cli.php command-crawl --url=devaka.ru
</pre>
<p>
	Запуск тестов phpunit:
</p>
<pre>
phpunit --bootstrap=../../tests/bootstrap.php phpunit/ParserTest.php
</pre>
