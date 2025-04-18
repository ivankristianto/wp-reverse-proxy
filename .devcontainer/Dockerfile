FROM wordpress:php8.2

# Allow devcontainer/Codespace use www-data as the remote user instead of root.
RUN usermod --shell /bin/bash www-data
RUN touch /var/www/.bashrc
RUN chown -R www-data: /var/www/

# Install git & zip
RUN apt-get update && \
	apt-get upgrade -y && \
	apt-get install -y git && \
	apt-get install -y sudo && \
	apt-get install -y zip && \
	apt-get install -y default-mysql-client

# Install Composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

# Install WP CLI
RUN curl -O https://raw.githubusercontent.com/wp-cli/builds/gh-pages/phar/wp-cli.phar && \
	chmod +x wp-cli.phar && \
	mv wp-cli.phar /usr/local/bin/wp

# Install nvm and node
RUN su www-data -c 'curl -o- https://raw.githubusercontent.com/nvm-sh/nvm/v0.35.3/install.sh | bash'
RUN su www-data -c 'export NVM_DIR="$HOME/.nvm" && [ -s "$NVM_DIR/nvm.sh" ] && \. "$NVM_DIR/nvm.sh" && nvm install 20'

# Allow www-data user to use sudo without password
RUN adduser www-data sudo
RUN echo '%sudo ALL=(ALL) NOPASSWD:ALL' >> /etc/sudoers
