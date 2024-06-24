# 기본 이미지 선택
FROM php:7.4-apache

# 패키지 업데이트 및 php-mysqli 설치, vim 설치
RUN apt-get update && apt-get install -y \
    libmariadb-dev-compat libmariadb-dev \
    vim \
    dos2unix \
    && docker-php-ext-install mysqli

# vim 설정 파일 복사
COPY vimrc /etc/vim/vimrc
RUN dos2unix /etc/vim/vimrc

# 필요한 경우 추가 설정 (예: 소스 코드 복사)
COPY . /var/www/html/
