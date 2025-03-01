# Product Management assesment

## Entity-Relationship Model

Products `products`
`id`, `name`, `description`, `price`, `category_id`, `image`, `created_at`, `updated_at`

Orders `orders`
`id`, `product_id`, `user_id`, `quantity`, `order_date`, `created_at`

Users `users`
`id`, `name`, `created_at`, `updated_at`

Categories `categories`
`id`, `name`, `created_at`, `updated_at`

![ERM Image](readme/MER.png "Entity-Relationship Model Image!")

## API Definition

- This solution implements the JSON:API standard for responses,
ensuring consistency and eliminating the need for custom formatting.
- Endpoints [Documentation](readme/api.yaml) for products

