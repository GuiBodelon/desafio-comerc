export interface Customer {
  id: number
  name: string
  email: string
  phone: string
  birth_date: string
  address: string
  complement: string
  neighborhood: string
  zip_code: string
  created_at?: string
  updated_at?: string
  deleted_at?: string | null
}
export interface Product {
  id: number
  name: string
  price: number
  photo?: string
  created_at?: string
  updated_at?: string
  deleted_at?: string | null
  pivot?: {
    order_id: number
    product_id: number
  }
}
// models.ts
export interface Order {
  id: number
  customer_id: number | null
  created_at: string
  updated_at: string
  customer_name: string
  total_price: number
  status: string
  deleted_at: string | null
  customer: Customer
  products: ProductWithPivot[]
}

export interface ProductWithPivot extends Product {
  pivot: {
    order_id: number
    product_id: number
    quantity: number
  }
}
