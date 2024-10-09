<?php
declare(strict_types=1);

namespace Tests;

use Fyre\FileSystem\Folder;
use Fyre\Make\MakeBehaviorCommand;
use Fyre\Make\MakeCellCommand;
use Fyre\Make\MakeCellTemplateCommand;
use Fyre\Make\MakeCommand;
use Fyre\Make\MakeCommandCommand;
use Fyre\Make\MakeConfigCommand;
use Fyre\Make\MakeControllerCommand;
use Fyre\Make\MakeElementCommand;
use Fyre\Make\MakeEntityCommand;
use Fyre\Make\MakeHelperCommand;
use Fyre\Make\MakeJobCommand;
use Fyre\Make\MakeLangCommand;
use Fyre\Make\MakeLayoutCommand;
use Fyre\Make\MakeMiddlewareCommand;
use Fyre\Make\MakeMigrationCommand;
use Fyre\Make\MakeModelCommand;
use Fyre\Make\MakeTemplateCommand;
use PHPUnit\Framework\TestCase;

final class MakeTest extends TestCase
{
    public function testMakeBehavior(): void
    {
        $makeBehavior = new MakeBehaviorCommand();
        $makeBehavior->run(['Example']);

        $filePath = 'tmp/Models/Behaviors/ExampleBehavior.php';

        $this->assertFileExists($filePath);

        $this->assertFileMatchesFormat(
            MakeCommand::loadStub('behavior', [
                '{namespace}' => 'Example\Models\Behaviors',
                '{class}' => 'ExampleBehavior',
            ]),
            $filePath
        );
    }

    public function testMakeCell(): void
    {
        (new MakeCellCommand())->run(['Example']);

        $filePath = 'tmp/Cells/ExampleCell.php';

        $this->assertFileExists($filePath);

        $this->assertFileMatchesFormat(
            MakeCommand::loadStub('cell', [
                '{namespace}' => 'Example\Cells',
                '{class}' => 'ExampleCell',
                '{method}' => 'display',
            ]),
            $filePath
        );
    }

    public function testMakeCellTemplate(): void
    {
        (new MakeCellTemplateCommand())->run(['Example.display']);

        $filePath = 'tmp/templates/cells/Example/display.php';

        $this->assertFileExists($filePath);

        $this->assertFileMatchesFormat(
            MakeCommand::loadStub('cell_template'),
            $filePath
        );
    }

    public function testMakeCommand(): void
    {
        (new MakeCommandCommand())->run(['Example']);

        $filePath = 'tmp/Commands/ExampleCommand.php';

        $this->assertFileExists($filePath);

        $this->assertFileMatchesFormat(
            MakeCommand::loadStub('command', [
                '{namespace}' => 'Example\Commands',
                '{class}' => 'ExampleCommand',
                '{alias}' => 'example',
                '{name}' => 'Example',
                '{description}' => '',
            ]),
            $filePath
        );
    }

    public function testMakeConfig(): void
    {
        (new MakeConfigCommand())->run(['example']);

        $filePath = 'tmp/config/example.php';

        $this->assertFileExists($filePath);

        $this->assertFileMatchesFormat(
            MakeCommand::loadStub('config'),
            $filePath
        );
    }

    public function testMakeController(): void
    {
        (new MakeControllerCommand())->run(['Example', 'namespace' => 'Example\Controllers']);

        $filePath = 'tmp/Controllers/ExampleController.php';

        $this->assertFileExists($filePath);

        $this->assertFileMatchesFormat(
            MakeCommand::loadStub('controller', [
                '{namespace}' => 'Example\Controllers',
                '{class}' => 'ExampleController',
            ]),
            $filePath
        );
    }

    public function testMakeElement(): void
    {
        (new MakeElementCommand())->run(['example']);

        $filePath = 'tmp/templates/elements/example.php';

        $this->assertFileExists($filePath);

        $this->assertFileMatchesFormat(
            MakeCommand::loadStub('element'),
            $filePath
        );
    }

    public function testMakeEntity(): void
    {
        (new MakeEntityCommand())->run(['Example']);

        $filePath = 'tmp/Entities/Example.php';

        $this->assertFileExists($filePath);

        $this->assertFileMatchesFormat(
            MakeCommand::loadStub('entity', [
                '{namespace}' => 'Example\Entities',
                '{class}' => 'Example',
            ]),
            $filePath
        );
    }

    public function testMakeHelper(): void
    {
        (new MakeHelperCommand())->run(['Example']);

        $filePath = 'tmp/Helpers/ExampleHelper.php';

        $this->assertFileExists($filePath);

        $this->assertFileMatchesFormat(
            MakeCommand::loadStub('helper', [
                '{namespace}' => 'Example\Helpers',
                '{class}' => 'ExampleHelper',
            ]),
            $filePath
        );
    }

    public function testMakeJob(): void
    {
        (new MakeJobCommand())->run(['Example', 'namespace' => 'Example\Jobs']);

        $filePath = 'tmp/Jobs/ExampleJob.php';

        $this->assertFileExists($filePath);

        $this->assertFileMatchesFormat(
            MakeCommand::loadStub('job', [
                '{namespace}' => 'Example\Jobs',
                '{class}' => 'ExampleJob',
            ]),
            $filePath
        );
    }

    public function testMakeLang(): void
    {
        (new MakeLangCommand())->run(['Example']);

        $filePath = 'tmp/lang/en/Example.php';

        $this->assertFileExists($filePath);

        $this->assertFileMatchesFormat(
            MakeCommand::loadStub('lang'),
            $filePath
        );
    }

    public function testMakeLayout(): void
    {
        (new MakeLayoutCommand())->run(['default']);

        $filePath = 'tmp/templates/layouts/default.php';

        $this->assertFileExists($filePath);

        $this->assertFileMatchesFormat(
            MakeCommand::loadStub('layout'),
            $filePath
        );
    }

    public function testMakeMiddleware(): void
    {
        (new MakeMiddlewareCommand())->run(['Example', 'namespace' => 'Example\Middleware']);

        $filePath = 'tmp/Middleware/ExampleMiddleware.php';

        $this->assertFileExists($filePath);

        $this->assertFileMatchesFormat(
            MakeCommand::loadStub('middleware', [
                '{namespace}' => 'Example\Middleware',
                '{class}' => 'ExampleMiddleware',
            ]),
            $filePath
        );
    }

    public function testMakeMigration(): void
    {
        (new MakeMigrationCommand())->run(['20240101']);

        $filePath = 'tmp/Migrations/Migration_20240101.php';

        $this->assertFileExists($filePath);

        $this->assertFileMatchesFormat(
            MakeCommand::loadStub('migration', [
                '{namespace}' => 'Example\Migrations',
                '{class}' => 'Migration_20240101',
            ]),
            $filePath
        );
    }

    public function testMakeModel(): void
    {
        (new MakeModelCommand())->run(['Example']);

        $filePath = 'tmp/Models/ExampleModel.php';

        $this->assertFileExists($filePath);

        $this->assertFileMatchesFormat(
            MakeCommand::loadStub('model', [
                '{namespace}' => 'Example\Models',
                '{class}' => 'ExampleModel',
            ]),
            $filePath
        );
    }

    public function testMakeTemplate(): void
    {
        (new MakeTemplateCommand())->run(['Example.index']);

        $filePath = 'tmp/templates/Example/index.php';

        $this->assertFileExists($filePath);

        $this->assertFileMatchesFormat(
            MakeCommand::loadStub('template'),
            $filePath
        );
    }

    protected function tearDown(): void
    {
        $folder = new Folder('tmp');
        $folder->delete();
    }
}
