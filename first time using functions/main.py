
def create_empty_inventory():
    return {f"Slot {i+1}": {"item": None, "quantity": 0} for i in range(27)}

def view_inventory(inventory):
    for slot, info in inventory.items():
        print(f"{slot}: {info['item']} x{info['quantity']}")

inventory = create_empty_inventory()

username = input("Hello user, please tell me your Minecraft username: ")

for count in range(27):
    print(f"\nInput for Slot {count + 1} (Enter 'done' to finish):")
    item = input(f"Item name (or 'done' to finish): ").strip()
    if item.lower() == 'done':
        break

    try:
        amount = int(input("Quantity: "))
        slot = int(input("Slot number (1 to 27): "))
        if 1 <= slot <= 27:
            inventory_key = f"Slot {slot}"
            inventory[inventory_key]['item'] = item
            inventory[inventory_key]['quantity'] = amount
        else:
            print("Invalid slot number. It should be between 1 and 27.")
    except ValueError:
        print("Please enter a valid number.")

# View the inventory
print("\nFinal Inventory:")
view_inventory(inventory)

