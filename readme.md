# Laravel PHP Framework

[![Build Status](https://travis-ci.org/laravel/framework.svg)](https://travis-ci.org/laravel/framework)
[![Total Downloads](https://poser.pugx.org/laravel/framework/d/total.svg)](https://packagist.org/packages/laravel/framework)
[![Latest Stable Version](https://poser.pugx.org/laravel/framework/v/stable.svg)](https://packagist.org/packages/laravel/framework)
[![Latest Unstable Version](https://poser.pugx.org/laravel/framework/v/unstable.svg)](https://packagist.org/packages/laravel/framework)
[![License](https://poser.pugx.org/laravel/framework/license.svg)](https://packagist.org/packages/laravel/framework)

### Laravel版本:5.2<br>
*特别记录：5.2版本没有Storage::putFile()方法*

## 项目介绍

本项目旨在：<br>
1、为企业提供一个多人维护的统一产品数据库；<br>
2、在线以“从产品库中选取产品”的形式编辑标书，减少手工劳作；<br>
3、方案（标书）撰写人员可以获得一个专属的方案空间，用于存放历次编写的标书；<br>
4、实现设备清单拖拽排序；<br>
5、提供excel及word导出功能；

## 特别使用的库
1、Ueditor<br>
2、Zicaco/Entrust<br>
3、Uploadify<br>
4、Phpoffice<br>
5、layer<br>
6、laydate<br>
7、slidepage

## 更新
2017.6.15
 - 个人方案表的设备默认排列顺序改为先插入的先于后插入的显示；
 - 修复了进出厂价格不显示的问题；
 - 修复了汇率的路由大小写问题；
 - 增加了全部设备的搜索选项；
 
2017.6.20
 - 解决了个人方案表刷新页面后排列顺序错误的bug；
 - 解决了个人方案表拖拽排序后分系统排列错误的bug；
 - 重构了部分代码，减少了数据库请求，同时增加了一点可读性。
 
本项目的不足之处：
 - 由于项目时间跨度较大，需求变化也较大，代码松散且耦合性较高，需要多次重构解决。

## License

The Laravel framework is open-sourced software licensed under the [MIT license](http://opensource.org/licenses/MIT)
