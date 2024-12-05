#!/usr/bin/env python3

import time
from enviroplus import gas
import logging
import json
import requests
from ltr559 import LTR559

ltr559 = LTR559()
from bme280 import BME280
from smbus import SMBus

bus = SMBus(1)
bme280 = BME280(i2c_dev=bus)

url = "https://api.creativecodingutrecht.nl/environmentals.php"

try:
    while True:
        gas_readings = gas.read_all()
        lux = ltr559.get_lux()
        proximity = ltr559.get_proximity()
        temperature = bme280.get_temperature()
        pressure = bme280.get_pressure()
        humidity = bme280.get_humidity()

        data_combined = {
            "gas_oxidising": gas_readings.oxidising,
            "gas_reducing": gas_readings.reducing,
            "gas_nh3": gas_readings.nh3,
            "gas_lux": lux,
            "gas_proximity": proximity,
            "temperature": temperature,
            "pressure": pressure,
            "humidity": humidity,
        }

        print(data_combined)
        res = requests.post(url, json=data_combined)

        time.sleep(5.0)
except KeyboardInterrupt:
    pass
