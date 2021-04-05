start:
	php -S localhost:8050
build:
	npm run-script build
install:
	mkdir db
	chmod 777 db
	npm update
	composer dump-autoload
	npm run-script build