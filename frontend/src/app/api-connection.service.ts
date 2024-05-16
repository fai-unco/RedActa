import { Injectable } from '@angular/core';
import { HttpClient } from '@angular/common/http';
import { environment } from '../environments/environment';
import { Observable } from 'rxjs';


@Injectable({
  providedIn: 'root'
})
export class ApiConnectionService {

  constructor(private http: HttpClient) { }

  get(urlSuffix: string, id?: string, options: any = {headers: {accept:'application/json'}}) {
    let url = `${environment.API_URL_BASE}/${urlSuffix}`;
    if (id) {
      url = `${url}/${id}`
    }
    return this.http.get(url, options);
  }

  post(urlSuffix: string, data: any) {
    return this.http.post(`${environment.API_URL_BASE}/${urlSuffix}`, data);
  }

  patch(resource: string, id: number, data: any, nestedResource?: string): Observable<any> {
    let url = `${environment.API_URL_BASE}/${resource}/${id}`;
    if (nestedResource) {
      url = `${url}/${nestedResource}`;
    }
    return this.http.patch(url, data);
  }

  delete(urlSuffix: string, id: string) {
    return this.http.delete(`${environment.API_URL_BASE}/${urlSuffix}/${id}`);
  }


}
