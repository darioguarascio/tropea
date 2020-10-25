<?php
// use Logger;

class Import {
    protected $log;
    protected $db;

    public function setUp() {
        $this->log = (new Logger(get_called_class())) ->getLogger();
    }


    public function perform()
    {
        try {
            dump(get_called_class() . ' => ', $this->args);

            return;

        } catch (\Exception $e) {
            $this->log->error($e->getMessagE(), [
                'input' => $this->args,
                'exception' => $e
            ]);
            throw $e;
        }
    }
}