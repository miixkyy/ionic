import { Injectable, Pipe } from '@angular/core';
import { HttpClientModule, HttpClient, HttpHeaders } from '@angular/common/http';
import { TopLevel, User } from '../interfaces';
import { Observable, pipe } from 'rxjs';
import { map } from 'rxjs/operators';


@Injectable({
  providedIn: 'root'
})
export class ApiService {
  public apiUrl = 'http://127.0.0.1:80/api1/method.php'; // Reemplaza con la URL de tu API
  public apiUrl_usuarios = 'http://127.0.0.1:80/api1/usuarios.php';
  constructor(private http: HttpClient) { }
getTopHeadlines(): Observable<TopLevel> {
  return this.http.get<TopLevel>('http://127.0.0.1:80/api1/method.php').pipe(
    map(resp => resp)
  );
}
  // Método para enviar datos por POST
postDatos(datos: any): Observable<any> {
  return this.http.post<any>(this.apiUrl, datos, { responseType: 'text' as 'json' });
}
eliminarDato(id_entrada: number): Observable<string> {
  return this.http.delete<string>(`${this.apiUrl}?id_entrada=${id_entrada}`, { responseType: 'text' as 'json' });
}
saveUserData(user: User): Observable<any> {
  const httpOptions = {
    headers: new HttpHeaders({ 'Content-Type': 'application/json' }),
    responseType: 'text' as 'json'
  };
  return this.http.post<any>(`${this.apiUrl_usuarios}`, user, {...httpOptions});
}
}
