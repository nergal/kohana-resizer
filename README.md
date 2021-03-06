## Kohana Resizer

This thing is for making image thumbnails on demand.

С помощью подобного подхода легко и удобно создавать превьюшки для больших изображений. Правда, этот модуль заточен под вполне определённые структуры как файлового хранилища, так и url.

### Сначала поясню структуру:

При загрузке файла он помещается в папку загрузок (глобально определена констнтой PUBDIR) в папку, сформированную из его хэша и переназывается по хэшу, при этом расширение остаётся исходное. К примеру, загруженный файл `123.jpg` с md5 хэшем 85f2d2818ab30e1278583a6d7c467c9e будет находиться по адресу `/uploads/85/f2d2/85f2d2818ab30e1278583a6d7c467c9e.jpg`, где `uploads` - значение константы PUBDIR, `85` - первые два симпола хэша и `f2d2` - часть хэша с третьего по шестой символ. Такой подход оправдан для равномерного распределения файлов по хранилищу, чтобы избежать накопления большого количества файлов в какой-то одной папке.

### Теперь про структуру урлов:

Чтобы сгенерировать превьюшку 120px на 80px для файла, приведенного выше, достаточно запросить его по адресу `http://site.com/thumb/cropr_120x80/85f2d2818ab30e1278583a6d7c467c9e.jpg`, где `thumb` - макрер (меняется в конфиге), `cropr` - тип превью, `120x80` - размер превью, а остальное - имя самого файла. Тип превью может быть одним из трёх:

 * `res` - обычный ресайз. Если пропорции не позволяют сделать точного размера, что размер изображения стремиться к минимуму.
 * `crop` - обычное обрезание изображения. Просто берётся указанное количество пикселей.
 * `cropr` - самый адекватный вариант, кроп с ресайзом. Сначала изображение обрезается до размера, когда самая короткая из сторон укладывается в указанные размеры, после чего обрезается до этих размеров.
 
Отресайзенные картинки складываются в папку кэша приложения и при следующем запросе отдаются уже оттуда.

