<?php

    //VARIABILI
    $token = "";

    $data = file_get_contents("php://input");
    /*Decodifica il json*/
    $update = json_decode($data, true);

    $message = $update["message"];
    $from = $message["from"];
    $text = $message["text"];
    $userID = $from["id"];
    $ChatID = $message["chat"]["id"];
    $username = $from["username"];
    $name = $from["first_name"];
    $cognome = $form["last_name"];

    $query = $update["callback_query"];
    $queryFrom = $query["from"];
    $queryUsername = $queryFrom["username"];
    $queryChatID = $update['callback_query']['message']['chat']['id'];
    $queryName = $queryFrom["first_name"];
    $queryID = $query["id"];
    $queryUserID = $queryFrom["id"];
    $queryData = $query["data"];
    $queryMsgID = $query["message"]["message_id"];

    //TASTIERE

    $Approve = [
                   [
                      [
                        'text' => "Si",
                      ],
                   ],
                   [
                      [
                          'text' => "No",
                      ],
                   ],
              ];

    $menu = [
                [
                    [
                        'text' =>   "⚽️ Comandi ⚽️",
                        'callback_data' =>  "commands",
                    ],
                    [
                        'text' => "👨🏼‍💻 Assistenza 👨🏼‍💻",
                        'callback_data' => "help",
                    ],
                ],
                [
                  [
                      'text' => "❓ Informazioni ❓",
                      'callback_data' => "info",
                  ],
                ],
            ];

    $Back = [
                [
                    [
                        'text' => "<<   Torna indietro    <<",
                        'callback_data' => "retry",
                    ],
                ],
            ];


    //COMANDI

    if(isset($message))
    {
        if(isset($username))
        {
            if(stripos($text,'/start') === 0)
            {
                if($ChatID > 0)
                {
                    sendMessage($ChatID,"Benvenuto $name [@$username].\n\n<i>Questo bot è stato creato con l'intento di aiutare la community di </i><b>Rocket League</b><i> con i trade. Per visionare le funzioni del bot clicca il bottone sottostante con su scritto </i>'<b>Comandi</b>'.<i> Per ricevere assistenza o suggerire qualche nuova funzione clicca il bottone con su scritto </i>'<b>Assistenza</b>'.\n\nQuesto bot è ancora in <b>BETA</b>, se doveste trovare dei bug contattatemi! @The_Luke.", $menu, "inline");
                }
                else
                    {
                        sendMessage($ChatID,"<b>Grazie per avermi aggiunto al gruppo! :D</b>\n\n<i>Questo bot è stato creato con l'intento di aiutare la community di </i><b>Rocket League</b><i> con i trade. Per visionare le funzioni del bot clicca il bottone sottostante con su scritto </i>'<b>Comandi</b>'.<i> Per ricevere assistenza o suggerire qualche nuova funzione clicca il bottone con su scritto </i>'<b>Assistenza</b>'.\n\nQuesto bot è ancora in <b>BETA</b>, se doveste trovare dei bug contattatemi! @The_Luke.", $menu, "inline");
                    }
            }

            //Creo un nuovo trade

            if(stripos($text,'/newtrade') === 0)
            {
                if($ChatID > 0)
                {
                    $double = false;
                    $file = file("search.txt");
                    $_text = explode(" ",$text);
                    for($i = 1;$i <= 6; $i++)
                    {
                      $_text[$i] = strtolower($_text[$i]);
                    }
                    $i = 0;
                    foreach($file as $i => $raw)
                    {
                        $search = explode(" ",$raw);
                        for($j = 0;$j <= 5; $j++)
                        {
                          $search[$j] = strtolower($search[$j]);
                        }
                        $user = strtolower($username);

                        if(($user == $search[0]) && ($_text[1] == $search[1]) && ($_text[2] == $search[2]) && ($_text[3] == $search[3]) &&($_text[4] == $search[4]) && ($_text[5] == $search[5]))
                        {
                            $double = true;
                        }
                    }

                    if($double == false)
                    {
                        $question = fopen("question.txt","w");
                        $trade = explode(" ",$text);

                        if(count($trade) == 6)
                        {
                            for($j = 0;$j < 6; $j++)
                            {
                                $trade[$j] = strtolower($trade[$j]);
                                $trade[$j] = ucfirst($trade[$j]);
                            }
                            sendMessage($ChatID,"Sicuro di voler inviare questo trade? Se il trade verrà ritenuto inadatto dallo staff verrà rimosso.\n\n\nTrade di @".$username."\n\nItem => ".$trade[1]."\nPaint => ".$trade[2]."\nCertified => ".$trade[3]."\nKey => ".$trade[4]."\nPlatform => ".$trade[5]."",$Approve,"fisica");
                            fwrite($question,"$username $trade[1] $trade[2] $trade[3] $trade[4] $trade[5] true");
                            fclose($question);
                        }
                        else
                            {
                                sendMessage($ChatID,"Errore, numero di paramentri inseriti errato. <b>ESEMPIO</b>\n\n/newtrade {ITEM} {PAINT} {CERTIFIED} {KEY} {PLATFORM}",$Back,"inline");
                            }
                    }
                    else
                        {
                            sendMessage($ChatID,"Errore, un tuo trade uguale a questo è già esistente.",$Back,"inline");
                            $double = false;
                        }
            }
            else
                {
                    sendMessage($ChatID,"[@$username]\n\nQuesto comando può essere usato solo in chat privata! >> @RLTradesBot",$Back,"inline");
                }
        }

            //Controllo del trade
            if($ChatID > 0)
            {
                if($text == "Si")
                {
                    $file = file("question.txt");
                    $i = 0;
                    foreach($file as $i => $raw)
                    {
                        $search = explode(" ",$raw);
                        for($j = 1; $j <= 5; $j++)
                        {
                            $search[$j] = strtolower($search[$j]);
                            $search[$j] = ucfirst($search[$j]);
                        }
                    }
                    if(($search[6] == "true")&&($text == "Si"))
                    {
                        $open = fopen("search.txt","a");
                        fwrite($open,"$username $search[1] $search[2] $search[3] $search[4] $search[5] +\n");
                        sendMessage($ChatID,"Ok! Aggiungo il tuo trade!",NULL,"rimuovi");
                        sendMessage($ChatID,"Trade aggiunto con successo",$Back,"inline");
                        fclose($open);
                    }
                    unset($file[$i]);
                    file_put_contents("question.txt",implode("",$file));
                }
                elseif($text == "No")
                      {
                          $file = file("question.txt");
                          $i = 0;
                          foreach($file as $i => $raw)
                          {
                              $search = explode(" ",$raw);
                              for($j = 1; $j <= 5; $j++)
                              {
                                  $search[$j] = strtolower($search[$j]);
                                  $search[$j] = ucfirst($search[$j]);
                              }
                          }
                          unset($file[$i]);
                          file_put_contents("question.txt",implode("",$file));
                          if(($search[6] == "true")&&($text == "No"))
                          {
                              sendMessage($ChatID,"Ok! Il tuo trade non verrà salvato!",NULL,"rimuovi");
                              sendMessage($ChatID,"Operazione Cancellata!",$Back,"inline");
                          }
                      }
                  }

            //Cancellazione trade

            if(stripos($text,'/deltrade') === 0)
            {
                if($ChatID > 0)
                {
                    $del = true;
                    $file = file("search.txt");
                    $_text = explode(" ",$text);
                    if(count($_text) == 6)
                    {
                        $del = false;
                        for($i = 1;$i <= 6; $i++)
                        {
                            $_text[$i] = strtolower($_text[$i]);
                        }
                        $i = 0;
                        foreach($file as $i => $raw)
                        {
                            $search = explode(" ",$raw);
                            for($j = 0;$j <= 5; $j++)
                            {
                              $search[$j] = strtolower($search[$j]);
                            }
                            $user = strtolower($username);

                            if(($user == $search[0]) && ($_text[1] == $search[1]) && ($_text[2] == $search[2]) && ($_text[3] == $search[3]) &&($_text[4] == $search[4]) && ($_text[5] == $search[5]))
                            {
                                unset($file[$i]);
                                file_put_contents("search.txt",implode("",$file));
                                sendMessage($ChatID,"Trade eliminato con successo.",$Back,"inline");
                                $del = true;
                            }
                         }
                      }
                      else
                          {
                              sendMessage($ChatID,"Errore, numero di paramentri inseriti errato.",$Back,"inline");
                          }
                    if($del == false)
                    {
                        sendMessage($ChatID,"Non puoi eliminare trade inesistenti.",$Back,"inline");
                    }
                }
                else
                    {
                        sendMessage($ChatID,"[@$username]\n\nQuesto comando può essere usato solo in chat privata! >> @RLTradesBot",$Back,"inline");
                    }
            }

            //Visualizzo tutti i trade dell'utente

            if(stripos($text,'/mytrade') === 0)
            {
                $flag = false;
                $message = fopen("message.txt","w");
                $file = file("search.txt");
                $i = 0;
                foreach($file as $i => $raw)
                {
                    $search = explode(" ",$raw);
                    for($j = 1; $j <= 5; $j++)
                    {
                        $search[$j] = strtolower($search[$j]);
                        $search[$j] = ucfirst($search[$j]);
                    }
                    if($search[0] == $username)
                    {
                        fwrite($message,"Trade di @".$search[0]."\n\nItem => ".$search[1]."\nPaint => ".$search[2]."\nCertified => ".$search[3]."\nKey => ".$search[4]."\nPlatform => ".$search[5]."\n\n///////////////////////\n\n");
                        $flag = true;
                    }
                }
                if($flag == false)
                {
                    sendMessage($ChatID,"Non ci sono tuoi trade in elenco.",$Back,"inline");
                }
                fclose($message);

                if($flag == true)
                {
                    $send = file_get_contents("message.txt");
                    sendMessage($ChatID,$send);
                }
            }

            //Visualizza tutti i trade

            if(stripos($text,'/alltrade') === 0)
            {
                if($ChatID > 0)
                {
                    $test = file_get_contents("search.txt");
                    if($test != false)
                    {
                        $file = file("search.txt");
                        $message = fopen("message.txt","w");
                        $i = 0;
                        foreach ($file as $i => $raw)
                        {
                          $search = explode(" ",$raw);
                          for($j = 1; $j <= 5; $j++)
                          {
                              $search[$j] = strtolower($search[$j]);
                              $search[$j] = ucfirst($search[$j]);
                          }
                          fwrite($message,"Trade di @".$search[0]."\n\nItem => ".$search[1]."\nPaint => ".$search[2]."\nCertified => ".$search[3]."\nKey => ".$search[4]."\nPlatform => ".$search[5]."\n\n///////////////////////\n\n");
                        }
                        fclose($message);
                        $send = file_get_contents("message.txt");
                        sendMessage($ChatID,$send);
                    }
                    else
                        {
                          sendMessage($ChatID,"Il bot non contiene trade al momento...",$Back,"inline");
                        }
                }
                else
                    {
                        sendMessage($ChatID,"[@$username]\n\nQuesto comando può essere usato solo in chat privata! >> @RLTradesBot",$Back,"inline");
                    }
            }

            //Cerca i trade tramite PLATFORM

            if(stripos($text,'/searchuser') === 0)
            {
                Search("user",NULL,NULL,0,$flag = false,NULL,NULL,0,0,$ChatID,$text,NULL,$Back);
            }

            //cerca i trade tramite username //cosa non ti funziona?

            if(stripos($text,'/searchitem') === 0)
            {
                Search(NULL,NULL,NULL,1,$flag = false,NULL,NULL,0,0,$ChatID,$text,NULL,$Back);
            }

            //Cerco i trade tramite item

            if(stripos($text,'/searchkey') === 0)
            {
                Search(NULL,NULL,NULL,4,$flag = false,NULL,NULL,0,0,$ChatID,$text,NULL,$Back);
            }

            //Cerco i trade tramite chiavi

            if(stripos($text,'/searchplat') === 0)
            {
                Search(NULL,NULL,NULL,5,$flag = false,NULL,NULL,0,0,$ChatID,$text,NULL,$Back);
            }
        }
        else
            {
                sendMessage($ChatID,"<b>[$name]</b>\n\nPer poter utilizzare il bot devi settarti un username!\n\n<b>Impostazioni > Username > Salva</b>\n\nSe sei riuscito a settare il tuo username digita\n                                        /start.\n\nSe stai avendo dei problemi contattami!\n                                   @The_Luke.");
            }
    }

    //Tastiera INLINE

    if(isset($query))
    {
        switch ($queryData)
        {
          case 'commands':
              editMessageText($queryChatID, $queryMsgID, "<b>Lista comandi del bot.</b>\n\n/newtrade {ITEM} {PAINT} {CERTIFIED} {KEY} {PLATFORM}\n  ^ Crea un nuovo Trade.\n\n/deltrade {ITEM} {PAINT} {CERTIFIED} {KEY} {PLATFORM}\n   ^ Elimina un tuo Trade.\n\n/searchkey {KEY}\n   ^ Cerca i Trade tramite key.\n\n/searchitem {ITEM}\n   ^ Cerca i Trade tramite item.\n\n/searchuser {USERNAME => Senza @}\n   ^ Cerca i Trade tramite username.\n\n/searchplatform {PLATFORM}\n   ^ Cerca i Trade tramite piattaforma.\n\n/mytrade => visualizza tutti i tuoi trade\n/alltrade => Visualizza tutti i trade", $Back, "inline");
              answerQuery($queryID);
              break;

          case 'retry':
            if($queryChatID > 0)
            {
                editMessageText($queryChatID, $queryMsgID,"Benvenuto $queryName [@$queryUsername].\n\n<i>Questo bot è stato creato con l'intento di aiutare la community di </i><b>Rocket League</b><i> con i trade. Per visionare le funzioni del bot clicca il bottone sottostante con su scritto </i>'<b>Comandi</b>'.<i> Per ricevere assistenza o suggerire qualche nuova funzione clicca il bottone con su scritto </i>'<b>Assistenza</b>'.\n\nQuesto bot è ancora in <b>BETA</b>, se doveste trovare dei bug contattatemi! @The_Luke.", $menu, "inline");
                answerQuery($queryID, "Torno indietro...", false);
            }
            else
                {
                  editMessageText($queryChatID, $queryMsgID,"<b>Grazie per avermi aggiunto al gruppo! :D</b>\n\n<i>Questo bot è stato creato con l'intento di aiutare la community di </i><b>Rocket League</b><i> con i trade. Per visionare le funzioni del bot clicca il bottone sottostante con su scritto </i>'<b>Comandi</b>'.<i> Per ricevere assistenza o suggerire qualche nuova funzione clicca il bottone con su scritto </i>'<b>Assistenza</b>'.\n\nQuesto bot è ancora in <b>BETA</b>, se doveste trovare dei bug contattatemi! @The_Luke.", $menu, "inline");
                  answerQuery($queryID, "Torno indietro...", false);
                }
          break;

          case 'help':
              editMessageText($queryChatID, $queryMsgID, "Ti serve aiuto o vuoi dare dei consigli? Non esitare a contattarmi :)\n\n@The_Luke", $Back, "inline");
          break;

          case 'info':
              editMessageText($queryChatID, $queryMsgID, "<b>Informazioni utili.</b>\n\n+ Lo staff del bot non si prende alcuna responsabilità se i vari trade sono insensati o grammaticalmente errati.\n\n+ Se l'item che volete scambiare non ha una colorazione o un certificato vi consigliamo di inserire dei caratteri speciali come:\n- /\n- *\n\n+ Se l'Item (o una sua caratteristica) che volete inserire contiene degli spazi come il '<b>Fire God</b>', utilizzate l'<b>underscore</b>! Questo vale anche quando lo cercate!\n- Fire_God\n- Forest_Green\n\n+ Il bot è Sensitive-Case per le ricerche tramite Username (/searchuser), ciò vuol dire che se scriverete '<b>CICCIO</b>' o '<b>ciccio</b>' per il bot saranno due cose diverse.", $Back, "inline");
          break;
        }
    }


    //FUNZIONI

    function sendMessage($ChatID, $text, $KeyBoard = NULL, $KType = "rimuovi")
    {
        $args = [
                  'chat_id' => $ChatID,
                  'text' => $text,
                  "parse_mode" => "HTML",
                ];

        if($KType == "inline")
        {
          if($KeyBoard != NULL)
          {
            $args['reply_markup'] = json_encode([
                                                  'inline_keyboard' => $KeyBoard,
                                                  'resize_keyboard' => true,
                                                ]);
          }
        }
        else if($KType == "fisica")
              {
                  if($KeyBoard != NULL)
                  {
                    $args['reply_markup'] = json_encode([
                                                          'keyboard' => $KeyBoard,
                                                          'resize_keyboard' => true,
                                                        ]);
                  }
              }
              else if($KType == "rimuovi")
                    {
                        $args['reply_markup'] = json_encode([
                                                              'remove_keyboard' => true,
                                                            ]);
                    }
                    else
                        {
                            $args['text'] = "Errore, controlla il KeyBoard type";
                        }
          return curlRequest('sendMessage', $args);
    }

    /*************************************************
    **************************************************
    ************************************************** SEPARO LE FUNZIONI
    **************************************************
    **************************************************/

    function editMessageText($query_chat_ID, $query_message_ID, $newText, $KeyBoard = NULL, $KType = "inline")
    {
        $args = [
                  "chat_id" => $query_chat_ID,
                  "message_id" => $query_message_ID,
                  "text" => $newText,
                  "parse_mode" => "HTML",
                ];
                if($KType == "inline")
                {
                  if($KeyBoard != NULL)
                  {
                    $args['reply_markup'] = json_encode([
                                                          'inline_keyboard' => $KeyBoard,
                                                          'resize_keyboard' => true,
                                                        ]);
                  }
                }
                else if($KType == "fisica")
                      {
                          if($KeyBoard != NULL)
                          {
                            $args['reply_markup'] = json_encode([
                                                                  'keyboard' => $KeyBoard,
                                                                  'resize_keyboard' => true,
                                                                ]);
                          }
                      }
                      elseif($KType == "rimuovi")
                            {
                                $args['reply_markup'] = json_encode([
                                                                      'remove_keyboard' => true,
                                                                    ]);
                            }
                            else
                                {
                                    $args['text'] = "Errore, controlla il KeyBoard type";
                                }
        return curlRequest('editMessageText', $args);
    }

    /*************************************************
    **************************************************
    ************************************************** SEPARO LE FUNZIONI
    **************************************************
    **************************************************/

    function answerQuery($callback_query_id, $text = "", $persistent = false)
    {
        $args = [
                  "callback_query_id" => $callback_query_id,
                  "text" => $text,
                  "show_alert" => $persistent,
                ];
        return curlRequest('answerCallbackQuery', $args);
    }

    /*************************************************
    **************************************************
    ************************************************** SEPARO LE FUNZIONI
    **************************************************
    **************************************************/

    function Search($method,$object,$search,$S_pos,$flag,$message,$file,$i,$j,$Chat_ID,$_text,$send,$Back)
    {
        $flag = false;
        $object = explode(" ",$_text);

        if(count($object) == 2)
        {
            if($method == "user")
            {
                $message = fopen("message.txt","w");
                $file = file("search.txt");
                $i = 0;
                foreach($file as $i => $raw)
                {
                    $search = explode(" ",$raw);
                    for($j = 1; $j <= 5; $j++)
                    {
                        $search[$j] = strtolower($search[$j]);
                        $search[$j] = ucfirst($search[$j]);
                    }

                    if($search[$S_pos] == $object[1])
                    {
                      fwrite($message,"Trade di @".$search[0]."\n\nItem => ".$search[1]."\nPaint => ".$search[2]."\nCertified => ".$search[3]."\nKey => ".$search[4]."\nPlatform => ".$search[5]."\n\n///////////////////////\n\n");
                      $flag = true;
                    }
                }

                if($flag == false)
                {
                    sendMessage($Chat_ID,"Trade inesistenti in base ai tuoi parametri.",$Back,"inline");
                }
                fclose($message);

                if($flag == true)
                {

                  $send = file_get_contents("message.txt");
                  sendMessage($Chat_ID,$send);
                }
            }
            else
                {
                    $message = fopen("message.txt","w");
                    $file = file("search.txt");
                    $i = 0;
                    foreach($file as $i => $raw)
                    {
                        $search = explode(" ",$raw);
                        for($j = 1; $j <= 5; $j++)
                        {
                            $search[$j] = strtolower($search[$j]);
                            $search[$j] = ucfirst($search[$j]);
                        }

                        $object[1] = strtolower($object[1]);
                        $object[1] = ucfirst($object[1]);

                        if($search[$S_pos] == $object[1])
                        {
                          fwrite($message,"Trade di @".$search[0]."\n\nItem => ".$search[1]."\nPaint => ".$search[2]."\nCertified => ".$search[3]."\nKey => ".$search[4]."\nPlatform => ".$search[5]."\n\n///////////////////////\n\n");
                          $flag = true;
                        }
                    }

                    if($flag == false)
                    {
                        sendMessage($Chat_ID,"Trade inesistenti in base ai tuoi parametri.",$Back,"inline");
                    }
                    fclose($message);

                    if($flag == true)
                    {
                        $send = file_get_contents("message.txt");
                        sendMessage($Chat_ID,$send);
                    }
                }
        }
        else
            {
                sendMessage($Chat_ID,"Errore, numero di paramentri inseriti errato.",$Back,"inline");
            }
    }

    /*************************************************
    **************************************************
    ************************************************** SEPARO LE FUNZIONI
    **************************************************
    **************************************************/

    function curlRequest($method, $args)
    {
        global $token;
        $c = curl_init();
        curl_setopt($c, CURLOPT_URL, "https://api.telegram.org/bot".$token."/".$method);
        curl_setopt($c, CURLOPT_POST, 1);
        curl_setopt($c, CURLOPT_POSTFIELDS, $args);
        curl_setopt($c, CURLOPT_RETURNTRANSFER, 1);
        $r = curl_exec($c);
        curl_close($c);
        return json_decode($r, true);
    }
?>
