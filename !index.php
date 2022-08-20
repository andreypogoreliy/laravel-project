<?php

    interface LoggerInterface
    {
        public function addRecord(LogRecord $record);
    }

    class LogRecord
    {
        public string $eventName;
        public DateTime $eventTime;

        public function __construct(string $eventName, DateTime $eventTime)
        {
            $this->eventName = $eventName;
            $this->eventTime = $eventTime;
        }
    }

    class FileLogger implements LoggerInterface
    {
        private string $filePath;

        public function __construct(string $file)
        {
            $this->filePath = $file;
        }

        public function addRecord(LogRecord $record)
        {
            $recordString = $record->eventName . ', ' . $record->eventTime->format('c') . PHP_EOL;
            $fp           = fopen($this->filePath, 'a');
            fwrite($fp, $recordString);
            fclose($fp);
        }

    }

    class StdOutLogger implements LoggerInterface
    {
        public function addRecord(LogRecord $record)
        {
            echo $record->eventName . ', ' . $record->eventTime->format('c') . '</br>';
        }

    }

    interface FactoryInterface
    {
        public function create(): LoggerInterface;
    }

    class FileFactory implements FactoryInterface
    {
        public function create(): LoggerInterface
        {
            return new FileLogger(__DIR__ . '/file.txt');
        }
    }


    class StdFactory implements FactoryInterface
    {
        public function create(): LoggerInterface
        {
            return new StdOutLogger();
        }
    }

    class LoggerController
    {
        public function handle(LoggerInterface $logger){
            $logger->addRecord(new LogRecord('new record', new DateTime()));
        }
    }
    $fileFactory = new FileFactory();
    (new LoggerController())->handle($fileFactory->create());
