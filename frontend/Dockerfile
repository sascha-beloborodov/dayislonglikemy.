FROM node:10-alpine

EXPOSE 3000
ENV HOST 0.0.0.0

WORKDIR /app

COPY package.json ./
RUN yarn

COPY . .

CMD yarn build && yarn start
