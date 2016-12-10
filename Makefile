install: vendor

vendor:
	./composer.phar install

test: install
	./vendor/bin/atoum

test-loop: install
	./vendor/bin/atoum --autoloop -ncc
