FROM python:3.12

EXPOSE 5000

RUN apt-get update \
  && apt-get install git -y \
  && apt-get clean

RUN pip install --no-cache-dir flask

WORKDIR /usr/src/app

RUN git clone https://github.com/hanze-hbo-ict/itvb23ows-hive-ai.git .

CMD [ "flask", "--app", "app", "run", "--host", "0.0.0.0", "--debug" ]
