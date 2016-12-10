install: vendor

vendor:
	./composer.phar install

test: install
	./vendor/bin/atoum
