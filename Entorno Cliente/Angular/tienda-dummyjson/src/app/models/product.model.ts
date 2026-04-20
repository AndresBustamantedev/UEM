export interface Product  { 
   id: number ; 
   title: string ; 
   price: number ; 
   category: string ; 
   brand: string ; 
   thumbnail: string ; 
 } 
 
 export interface ProductsResponse  { 
   products: Product []; 
   total: number ; 
   skip: number ; 
   limit: number ; 
 }