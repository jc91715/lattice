<h1 align="center"> lattice </h1>

<p align="center"> A Php Lattice.</p>


## Installing

```shell
$ composer require jc91715/lattice -vvv
```

## Usage

```
use Jc91715\Lattice\Lattice;

$str="hello world";
$lattice = new Lattice($str);

echo $lattice->getResult();
```

## Contributing

You can contribute in one of three ways:

1. File bug reports using the [issue tracker](https://github.com/jc91715/lattice/issues).
2. Answer questions or fix bugs on the [issue tracker](https://github.com/jc91715/lattice/issues).
3. Contribute new features or update the wiki.

_The code contribution process is not very formal. You just need to make sure that you follow the PSR-0, PSR-1, and PSR-2 coding guidelines. Any new code contributions must be accompanied by unit tests where applicable._

## License

MIT