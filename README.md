# Zoöp Instrumentarium

## BirdNET-PI
The BirdNET-PI is a realtime acoustic bird classification system for the Raspberry Pi 4B, 400, 3B+, and 0W2.

BirdNET-Pi is built on the [BirdNET framework](https://github.com/kahst/BirdNET-Analyzer) by [@kahst](https://github.com/kahst) using [pre-built TensorFlow Lite binaries](https://github.com/PINTO0309/TensorflowLite-bin) by [@PINTO0309](https://github.com/PINTO0309). It is able to recognize bird sounds from a USB microphone or sound card in realtime and share its data with the rest of the world.

### Installation

Following the [installation instructions](https://github.com/mcguirepr89/BirdNET-Pi/wiki/Installation-Guide) on the [BirdNET-PI website](https://www.birdweather.com/birdnetpi) to start identifying birds and sharing the results with the world (i.e. Bird Weather).

## BirdWeather
[BirdWeather](https://www.birdweather.com/) is a pioneering visualization platform that harnesses the [BirdNET](https://birdnet.cornell.edu/) artificial neural network to monitor bird vocalizations globally through 2000 active audio stations (and growing). 

The living library of bird vocalizations can be found on [BirdWeather's Live Map](https://app.birdweather.com).

### Connect BirdNET-PI with BirdWeather
After [creating an account](https://app.birdweather.com/login) on BirdWeather you receive a **BirdWeather ID**.
Once that’s done - you can [create and manage your station](https://app.birdweather.com/account/stations).

To connect BirdNET-PI with BirdWeather, configure your BirdWeather ID under "Basic Settings" of your BirdNET-PI. 
Also make sure that the Latitude and Longitude match what is in your BirdNET-Pi configuration.

## Enviro+ for Raspberry Pi 
Designed for environmental monitoring, [Enviro+ for Raspberry Pi](https://shop.pimoroni.com/products/enviro) lets you measure air quality (pollutant gases and particulates), temperature, pressure, humidity, light, and noise level. When combined with a particulate matter sensor, it's great for monitoring air quality just outside your house (more information below), or without the particulate sensor you can use it to monitor indoor conditions.

### Installation
https://learn.pimoroni.com/article/getting-started-with-enviro-plus
