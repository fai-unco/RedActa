import { Injectable } from '@angular/core';
import { HttpClient } from '@angular/common/http';
import { environment } from '../environments/environment';


@Injectable({
  providedIn: 'root'
})
export class ApiConnectionService {

  constructor(private http: HttpClient) { }

  get(urlSuffix: string, id?: string){
    let url = `${environment.API_URL_BASE}/${urlSuffix}`;
    if(id) {
      url = `${url}/${id}`
    }
    return this.http.get(url);
  }

  post(urlSuffix: string, data: any){
    return this.http.post(`${environment.API_URL_BASE}/${urlSuffix}`, data);
  }

  patch(urlSuffix: string, id: string, data: any){
    return this.http.patch(`${environment.API_URL_BASE}/${urlSuffix}/${id}`, data);
  }

  delete(urlSuffix: string, id: string){
    return this.http.delete(`${environment.API_URL_BASE}/${urlSuffix}/${id}`);
  }


}
