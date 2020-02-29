import requests
import re
import csv

def open_url(url):
    res = requests.get(url)
    return res

def findInfo(url, re_wantInfo):
    res = open_url(url)
    html = str(res.text)
    reg = re.compile(re_wantInfo)
    contents = re.findall(re_wantInfo, html)
    return contents

def saveToCSV(filename, contents):
    with open(filename, 'a+', newline='', encoding='gb18030') as f:
        writer = csv.writer(f)
        for each in contents:
            each = [each[1], each[0]]
            writer.writerow(each)
    

url = 'https://www.cnblogs.com/txw1958/default.html?page='
re_wantInfo = r'<div class="postTitle">\s*?<a id=".*?" class="postTitle2" href="(.*?)">(.*?)</a>'


for page in range(1, 27):
    dangqian_url = url + str(page)
    contents = findInfo(dangqian_url, re_wantInfo)
    #print(contents)
    saveToCSV('微信标题.csv', contents)

    

