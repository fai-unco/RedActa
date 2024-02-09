import { map } from "rxjs/operators";
import { ApiConnectionService } from "../api-connection.service";
import { Injectable } from "@angular/core";

@Injectable({
    providedIn: 'root'
})
export class DocumentService {

    constructor (private connectionService: ApiConnectionService){}

    exportDocument (documentId: any, isCopy = false, addBlankPageAtEnd = false) {
        return this.connectionService.get('documents', documentId, {headers: {accept:'application/pdf'}, responseType: 'blob', observe: 'response', params: {is_copy: isCopy, addBlankPageAtEnd: addBlankPageAtEnd}})
            .pipe(map((res: any) => {
                    let file = new Blob([res.body], {type: 'application/pdf'});
                    let fileURL = URL.createObjectURL(file);
                    const link = document.createElement('a');
                    let filename='file.pdf';
                    const source = fileURL;
                    link!.href = source;
                    let contentDispositionHeader = res.headers.get('Content-Disposition');
                    if (contentDispositionHeader) {
                        var filenameRegex = /filename[^;=\n]*=((['"]).*?\2|[^;\n]*)/;
                        var matches = filenameRegex.exec(contentDispositionHeader);
                        if (matches != null && matches[1]) { 
                            filename = matches[1].replace(/['"]/g, '');
                        }
                    }
                    link!.download = filename;
                    link.click();
                }
            ))
    }

}