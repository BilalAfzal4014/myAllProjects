class CreateUserOrders < ActiveRecord::Migration[6.0]
  def change
    create_table :user_orders do |t|
      t.bigint :user_id
      t.string :type
      t.string :price

      t.timestamps
    end

    execute <<-SQL
      ALTER TABLE user_orders
        ADD CONSTRAINT fk_user_user_orders
        FOREIGN KEY (user_id)
        REFERENCES users(id)
    SQL
  end
end
