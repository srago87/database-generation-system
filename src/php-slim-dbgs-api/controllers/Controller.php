<?php

    include_once('databases/Database.php');


    use Psr\Container\ContainerInterface;

    class Controller {
    
        private $container;
        private $pdo;
        private $logger;
        private $db;

        // constructor receives container instance
        public function __construct(ContainerInterface $container, $database) {
            $this->container = $container;
            $this->db = $database;
            $this->pdo = $this->container['db'];
            $this->logger = $this->container['logger'];
        }

        public function loadFromData($data) {
            return null;
        }


        public function create($request, $response, $args) {
            $data = $request->getParams();

            $inputRecord = $this->loadFromData($data); 
            $this->logger->debug("create: " . json_encode($inputRecord));
            $returnedObject = $this->db->create($inputRecord);
            $this->logger->debug("create2: " . json_encode($returnedObject));

            $response = $response->withHeader('Content-type', 'application/json');
            //retuns JSON payload with 201 response
            $response = $response->withJson($returnedObject, 201); //json_encode()
            return $response;
        }
        
        public function list($request, $response, $args) {
            $returnedObject = $this->db->readList();
            
            //return a 404 reponse when there are no inefficiencies in a gevin domain.
            if(count( $returnedObject) == 0) {
                $response = $response->withStatus(404);
                return $response;
            }
             
            $response = $response->withHeader('Content-type', 'application/json');
            //retuns JSON payload with 201 response
            $response = $response->withJson($returnedObject, 201);
            return $response;
        }

       

        public function get($request, $response, $args) {
            // id Parameters
            $id = intval($args['id']);
            $returnedObject = $this->db->read($id);  
            //return a 404 reponse when there are no inefficiencies in a gevin domain.
            if($returnedObject == null) {
                $response = $response->withStatus(404);
                return $response;
            }

            $response = $response->withHeader('Content-type', 'application/json');
            //retuns JSON payload with 201 response
            $response = $response->withJson($returnedObject, 201);
            return $response;
        }

        public function edit($request, $response, $args) {
            $data = $request->getParams();

            $inputRecord = $this->loadFromData($data);

            $id = $args['id'];
            
            $outputRecord = $this->db->update($inputRecord);

            $response = $response->withHeader('Content-type', 'application/json');
            //retuns JSON payload with 201 response
            $response = $response->withJson($outputRecord, 201);
            return $response;
        }

        public function delete($request, $response, $args) {
            $id = $args['id'];
            
            $outputRecord = $this->db->delete($id);

            $response = $response->withHeader('Content-type', 'application/json');
            //retuns JSON payload with 201 response
            $response = $response->withJson($outputRecord, 201);
            return $response;
        }
    }
?>
