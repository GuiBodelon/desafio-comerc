import { api } from 'boot/axios'

export const orderService = {
  // Buscar produtos
  async fetchProducts() {
    try {
      const response = await api.get('/products')
      return response.data
    } catch (error) {
      console.log(error)
      throw new Error('Erro ao carregar produtos')
    }
  },

  // Enviar pedido
  async submitOrder(orderData: unknown) {
    try {
      await api.post('/orders', orderData)
    } catch (error) {
      console.log(error)
      throw new Error('Erro ao enviar pedido')
    }
  },

  // Buscar cliente por e-mail
  async findCustomer(email: string) {
    try {
      const response = await api.post('/customers/find', { email })
      return response.data
    } catch (error) {
      console.log(error)
      throw new Error('Erro ao buscar cliente')
    }
  },

  // Criar novo cliente
  async createCustomer(customerData: unknown) {
    try {
      await api.post('/customers', customerData)
    } catch (error) {
      console.log(error)
      throw new Error('Erro ao criar cliente')
    }
  },

  // Criar ou buscar cliente e enviar pedido
  async createOrder(email: string, productData: { id: number; quantity: number }[]) {
    try {
      // Buscar o cliente pelo email
      let customer = await this.findCustomer(email)

      // Se o cliente não for encontrado, cria um novo cliente
      if (!customer) {
        const customerData = {
          // Dados do novo cliente, adicione conforme necessário
          name: 'Nome do Cliente',
          email: email,
          phone: '000000000',
          birth_date: '01/01/1990',
          address: 'Endereço do Cliente',
          complement: 'Complemento',
          neighborhood: 'Bairro',
          zip_code: '00000000',
        }
        customer = await this.createCustomer(customerData)
      }

      // Montar os produtos com suas quantidades
      const products = productData.map((item) => ({
        product_id: item.id,
        quantity: item.quantity,
      }))

      // Enviar o pedido com produtos e suas quantidades
      const orderData = {
        customer_id: customer.id, // Associando o cliente encontrado ou criado
        products: products, // Os produtos com suas quantidades
      }

      // Chama a função para enviar o pedido
      await this.submitOrder(orderData)

      return { success: true, message: 'Pedido realizado com sucesso' }
    } catch (error) {
      console.log(error)
      throw new Error('Erro ao criar pedido')
    }
  },
}
