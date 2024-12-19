# Zoöp Instrumentarium

## BirdNET-PI
The BirdNET-PI is a realtime acoustic bird classification system for the Raspberry Pi 4B, 400, 3B+, and 0W2.

BirdNET-Pi is built on the [BirdNET framework](https://github.com/kahst/BirdNET-Analyzer) by [@kahst](https://github.com/kahst) using [pre-built TensorFlow Lite binaries](https://github.com/PINTO0309/TensorflowLite-bin) by [@PINTO0309](https://github.com/PINTO0309). It is able to recognize bird sounds from a USB microphone or sound card in realtime and share its data with the rest of the world.

### Installation

Follow the [installation instructions](https://github.com/mcguirepr89/BirdNET-Pi/wiki/Installation-Guide) on the [BirdNET-PI website](https://www.birdweather.com/birdnetpi) to start identifying birds and sharing the results with the world (i.e. Bird Weather).

NB: THe latest version of Raspberry Pi OS (Debian Bookworm) has issues during installation (see this [GitHub issue](https://github.com/mcguirepr89/BirdNET-Pi/issues/1049)). Easiest solution is to select `Raspberry Pi OS (Legacy, 64-bit) Lite` (Debian Bullseye). The installation (and BirdNET-Pi itself) runs without issues on this version of the OS.

## BirdWeather
[BirdWeather](https://www.birdweather.com/) is a pioneering visualization platform that harnesses the [BirdNET](https://birdnet.cornell.edu/) artificial neural network to monitor bird vocalizations globally through 2000 active audio stations (and growing). 

The living library of bird vocalizations can be found on [BirdWeather's Live Map](https://app.birdweather.com).

### Connect BirdNET-PI with BirdWeather
After [creating an account](https://app.birdweather.com/login) on BirdWeather you receive a **BirdWeather ID**.
Once that’s done - you can [create and manage your station](https://app.birdweather.com/account/stations).

To connect BirdNET-PI with BirdWeather, configure your BirdWeather ID under "Basic Settings" of your BirdNET-PI. 
Also make sure that the Latitude and Longitude match what is in your BirdNET-Pi configuration.

### Connect BirdNET-PI with Telegram 
The BirdNET-PI uses Apprise to send notifications. You can send these notifications to multiple destinations, including Telegram. For more detailed instructions, see [Apprise documentation for using Telegram](https://github.com/caronc/apprise/wiki/Notify_telegram).

Do you want to follow detections with multiple people? Our suggestion is to create a public Telegram Group to collect BirdNET-Pi notifications. To achieve this, add your Telegram Bot to this group and use the `chatid` of the group when configuring the Apprise notification via Telegram on your BirdNET-Pi.

## Enviro+ for Raspberry Pi 
Designed for environmental monitoring, [Enviro+ for Raspberry Pi](https://shop.pimoroni.com/products/enviro) lets you measure air quality (pollutant gases and particulates), temperature, pressure, humidity, light, and noise level. When combined with a particulate matter sensor, it's great for monitoring air quality just outside your house (more information below), or without the particulate sensor you can use it to monitor indoor conditions.

### Installation
Follow the [installation instructions](https://learn.pimoroni.com/article/getting-started-with-enviro-plus) to allow Python scripts to read the sensor data from the Enviro+.

Sensors:
*   BME280 temperature/pressure/humidity sensor (NB: Temperature must be calibrated/corrected)
*   PMS5003 particulate sensor

### CCU Enviro+ Export Service
The folder `/enviroplus/client` contains a Python script and a unit configuration file to run this script as a Systemd service on the Raspberry Pi.

The folder `/enviroplus/server` contains a PHP script that is able to receive and return data from the Enviro+ export script. This PHP script uses a MySQL database to store the Enviro+ metrics.

#### Installation on the Raspberry Pi

First, install as a Systemd service:

```
sudo cp enviroplus-export.service /etc/systemd/system
sudo chmod 644 /etc/systemd/system/enviroplus-export.service
sudo systemctl daemon-reload
```

Then, enable and start the `enviroplus-export` service:

```
sudo systemctl enable --now enviroplus-export
```

Check the status of the service:

```
sudo systemctl status enviroplus-export
```

If the service is running correctly, the output should resemble the following:

```
● enviroplus-export.service - Enviroplus Export
     Loaded: loaded (/etc/systemd/system/enviroplus-export.service; enabled; vendor preset: enabled)
     Active: active (running) since Thu 2024-12-05 15:46:38 CET; 4s ago
   Main PID: 6785 (start.sh)
      Tasks: 2 (limit: 1599)
        CPU: 423ms
     CGroup: /system.slice/enviroplus-export.service
             ├─6785 /bin/bash /home/ccu/enviroplus/start.sh
             └─6786 python3 enviroplus-export.py

Dec 05 15:46:38 birdpi systemd[1]: Started Enviroplus Export.
```

The data can be visualized using [this sketch](https://rein.computer/sketches/ccu/environmental-data.html).

## Data platform ideas

### Current implementation

#### BirdNET-Pi
The current version of the BirdNET-Pi at CCU is connected to the [BirdWeather's Live Map](https://app.birdweather.com). All data recorded in this service is available though the [BirdWeather Data Explorer](https://app.birdweather.com/data/aj2YdMtuzSF7vTDfYDtXkjCP). 

To access this data with software, we can use the public [BirdWeather GraphQL API](https://app.birdweather.com/api/index.html).

#### Enviro+
The current version of the CCU Enviro+ Export Service is a simple mechanism to collect Enviro+ metrics from the sensors connected to the Raspberry Pi. The data is uploaded to a service hosted on the internet. This data can then be visualised.

### Internet of Things (IoT) monitoring /w Prometheus and Grafana
A versatile idea to collect the metrics from BirdNET-PI and Enviro+ would be pushing sensor metrics for analysis and visualization from Raspberry Pi to a [Prometheus](https://prometheus.io/) server allowing this data to be visualized with [Grafana OSS](https://grafana.com/oss/grafana/). Prometheus an open-source systems monitoring and alerting toolkit originally built at SoundCloud.  

#### Communication
Options for communication between the Raspberry Pi and the services with:
*   Ethernet (LAN) connection access to internet
*   WiFi connection with access to internet
*   LoRaWAN connection with access to an internet gateway (e.g. ThingsNetwork)

#### Services hosted on the internet
*   Prometheus (pulls data from data providers)
*   Grafana OSS

#### Intermediate services for collecting data from Raspberry Pi
*   Prometheus Push Gateway on the internet (https://github.com/prometheus/pushgateway) - acts as a data provider for Prometheus. This service receives Enviro+ metrics from the Raspberry Pi over the internet ([article](https://www.metricfire.com/blog/prometheus-pushgateways-everything-you-need-to-know/#strongSending-Metricsstrong)).
*   [The Things Network](https://www.thethingsnetwork.org/) - a global collaborative Internet of Things ecosystem that creates networks, devices and solutions using LoRaWAN ([article](https://lupyuen.github.io/articles/prometheus)).

#### Client services on Raspberry Pi
*   Custom data export script (Python)
    *   Uses Python Enviro+ library for reading sensors 
    *   Send sensor metrics to intermediate service (e.g. Prometheus Push Gateway or The Things Network)
    *   Run the script as a service on the Raspberry Pi, similar to how this is solved in [EnviroPlus exporter](https://github.com/tijmenvandenbrink/enviroplus_exporter/blob/master/contrib/enviroplus-exporter.service).





