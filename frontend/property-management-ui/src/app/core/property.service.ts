import { Injectable } from '@angular/core';
import { HttpClient } from '@angular/common/http';
import { environment } from '../../../src/environments/environment';
import { Observable } from 'rxjs';

@Injectable({
  providedIn: 'root'
})
export class PropertyService {
  private api = environment.apiUrl + '/property';

  constructor(private http: HttpClient) {}

  getAll(): Observable<any> {
    return this.http.get(this.api);
  }

  getById(id: number): Observable<any> {
    return this.http.get(`${this.api}/${id}`);
  }

  create(data: any): Observable<any> {
    return this.http.post(this.api, data);
  }

  update(id: number, data: any): Observable<any> {
    return this.http.put(`${this.api}/${id}`, data);
  }

  delete(id: number): Observable<any> {
    return this.http.delete(`${this.api}/${id}`);
  }
}
