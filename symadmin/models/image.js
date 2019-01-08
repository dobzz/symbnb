module.exports = (sequelize, DataTypes) => {
  const Model = sequelize.define('image', {
    'url': {
      type: DataTypes.STRING,
    },
    'caption': {
      type: DataTypes.STRING,
    },
  }, {
    tableName: 'image',
    
    timestamps: false,
    
  });

  Model.associate = (models) => {
    Model.belongsTo(models.ad, {
      foreignKey: 'ad_id',
      
      as: '_ad_id',
    });
    
  };

  return Model;
};

