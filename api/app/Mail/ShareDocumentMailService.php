<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;
use App\Models\RedactaUser;


class ShareDocumentMailService extends Mailable

{

    use Queueable, SerializesModels;

    /**

     * Create a new message instance.

     *

     * @return void

     */

    /*public function __construct()

    {

        //

    }*/

    public function __construct(
        protected int $documentId,
        protected RedactaUser $sender,
        protected string $documentName
    ) {}

    /**

     * Build the message.

     *

     * @return $this

     */

    public function build()
    {
        $senderFullName = $this->sender->name.' '.$this->sender->last_name;
        return $this->view('emails.share-document')
            ->replyTo($this->sender->email, $senderFullName)
            ->subject('Invitación a documento: '.$this->documentName)
            ->with([
                'url' => env('APP_URL').'/documentos/editar?id='.$this->documentId,
                'sender' => $senderFullName,
                'documentName' => $this->documentName
            ]);
    }

}