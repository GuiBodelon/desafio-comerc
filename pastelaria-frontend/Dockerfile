# Stage de desenvolvimento
FROM node:18-alpine as develop-stage

# Defina o diretório de trabalho
WORKDIR /app

# Copiar apenas os arquivos de dependências primeiro para otimizar o cache de build
COPY package*.json ./

# Instalar o Quasar CLI globalmente
RUN yarn global add @quasar/cli

# Copiar o restante dos arquivos do projeto
COPY . .

# Stage de build
FROM develop-stage as build-stage

# Instalar as dependências do projeto
RUN yarn

# Rodar o build do Quasar
RUN quasar build

# Stage de produção
FROM nginx:alpine as production-stage

# Copiar os arquivos construídos para o Nginx
COPY --from=build-stage /app/dist/spa /usr/share/nginx/html

# Expor a porta 80 para acesso ao servidor
EXPOSE 80

# Comando para rodar o Nginx
CMD ["nginx", "-g", "daemon off;"]