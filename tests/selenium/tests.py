from selenium.webdriver.firefox.options import Options
from selenium.webdriver import Firefox
from selenium.webdriver.common.by import By

options = Options()

#options.add_argument("--headless")

#driver = Firefox(options=options)
driver = Firefox()

driver.get("http://localhost:3000")

siteTitleHeader = driver.find_element(By.CLASS_NAME, 'siteTitleHeader')

driver.quit()
